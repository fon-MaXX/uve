<?php
namespace Site\BackendBundle\NewPost;
use Site\BackendBundle\Entity\NovaPoshtaData;
use Site\BackendBundle\Entity\NovaPoshtaRegion;
use Site\BackendBundle\Entity\Order;
use Symfony\Component\DependencyInjection\Container;

class NewPostService{
    public $apiKey = "ae67c92f0b0dbae144969d0d49e40627";
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function trackDocuments($documents){
        $params=array(
            'Documents' => $documents
        );
        $data = array(
            'apiKey' => $this->apiKey,
            'modelName' => "InternetDocument",
            'calledMethod' => "documentsTracking",
            'methodProperties' => $params
        );
        $post = json_encode($data);
        $ch = curl_init("https://api.novaposhta.ua/v2.0/json/");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $result = curl_exec($ch);
        curl_close($ch);
        $documents = json_decode($result,1);
        $arr = array();
        if(isset($documents['data'])&&count($documents['data'])){
            foreach($documents['data'] as $doc){
                    $item = [
                        'newPostStateId' => $doc['StateId'],
                        'newPostState' => $doc['StateName']
                    ];
                    if(strlen($doc['GlobalMoneyLastTransactionStatus'])){
                        $item['codState'] = $doc['GlobalMoneyLastTransactionStatus'];
                    }
                    else{
                        $item['codState'] = null;
                    }
                    if(isset($doc['GlobalMoneyLastTransactionDate']['date'])){
                        $item['codTransactionDate'] = $doc['GlobalMoneyLastTransactionDate']['date'];
                    }
                    else{
                        $item['codTransactionDate']=null;
                    }
                    $arr[$doc['Barcode']]=$item;
            }
        }
        return $arr;
    }
    /**
     * get list of Ukraine regions
     *
     */

    public function getRegion($regionHref)
    {
        $em = $this->container->get('doctrine')->getManager();
        $region = $em->getRepository('SiteBackendBundle:NovaPoshtaRegion')->findOneBy([
            'ref' => $regionHref
        ]);
        return $region;
    }

    public function getRegions()
    {
        $em = $this->container->get('doctrine')->getManager();
        $regions = $em->getRepository('SiteBackendBundle:NovaPoshtaRegion')->findAll();
        return $regions;
    }
    /**
     * get Cities for region
     *
     */
    public function getCities(NovaPoshtaRegion $region)
    {
        $params=array(
            'Page' => 0,
            'FindByString' => '',
            'Ref' => '',
        );
        $data = array(
            'apiKey' => $this->apiKey,
            'modelName' => "Address",
            'calledMethod' => "getCities",
            'language' => "ru",
            'methodProperties' => $params
        );
        $post = json_encode($data);
        $ch = curl_init("https://api.novaposhta.ua/v2.0/json/");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $result = curl_exec($ch);
        curl_close($ch);
        $cities = json_decode($result,1);
        $arr = new \SplObjectStorage();
        if(isset($cities['data'])&&count($cities['data'])){
            foreach($cities['data'] as $city){
                if($city['Area']==$region->getRef()){
                    $item = (object)[
                        'description' => $city['Description'],
                        'ref' => $city['Ref'],
                        'area' => $city['Area']
                    ];
                    $arr->attach($item);
                }
            }
        }
        return $arr;
    }

    /**
     * get list of available warehouses for city
     *
     */
    public function getWarehouses(\stdClass $city)
    {
        $params=array(
            'CityRef' => $city->ref,
            'Page' => 0
        );
        $data = array(
            'apiKey' => $this->apiKey,
            'modelName' => "Address",
            'calledMethod' => "getWarehouses",
            'language' => "ru",
            'methodProperties' => $params
        );
        $post = json_encode($data);
        $ch = curl_init("https://api.novaposhta.ua/v2.0/json/");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $result = curl_exec($ch);
        curl_close($ch);
        $warehouses = json_decode($result,1);
        $arr = new \SplObjectStorage();
        if(isset($warehouses['data'])&&count($warehouses['data'])){
            foreach($warehouses['data'] as $warehouse){
                $item = (object) [
                    'description' => $warehouse['Description'],
                    'ref' => $warehouse['Ref'],
                    'city' => $warehouse['CityRef']
                ];
                $arr->attach($item);
            }
        }
        return $arr;
    }

    /**
     * Creates invoce, return Ref of just created invoice
     *
     * @param Order $order
     * @return bool
     */
    public function createInvoice(Order $order)
    {
        if(!$this->checkRecipient($order)){
            return false;
        }
        $newPostData = $order->getNovaPoshtaData();
        $recipient = $this->createRecipient($order);
        $sender = $this->getSender();
        if(
            $order->getNovaPoshtaData()->getPayer()=='Sender'
        ){
            $payer='Sender';
        }
        else{
            $payer='Recipient';
        }
        $params = (array)[
            'PayerType' => $payer,
            'PaymentMethod' => 'Cash',
            'DateTime' => date('d.m.Y', time()),
            'CargoType'=>'Cargo',
            'VolumeGeneral' => '0.0004',
            'Weight' => '0,4',
            'ServiceType' => 'WarehouseWarehouse',
            'SeatsAmount' => '1',
            'Description' => 'аксесуари до мобільних пристроїв',
            'Cost' => $order->getSumm(),
            'CitySender' => $sender->CitySenderRef,
            'Sender' => $sender->SenderRef,
            'SenderAddress' => $sender->SenderWarehouseAdressRef,
            'ContactSender' => $sender->ContactSender,
            'SendersPhone' => $sender->SendersPhone,
            'CityRecipient'=> $newPostData->getCityHref(),
            'Recipient' => $recipient->Ref,
            'RecipientAddress' => $newPostData->getWarehouseHref(),
            'ContactRecipient' => $recipient->ContactRecipient,
            'RecipientsPhone' => $this->preparePhone($order->getTelephone()),
            'OptionsSeat'=>(array)[
                0=>(array)[
                'volumetricVolume'=>"0,03",
                "volumetricWidth"=>"0,3",
                "volumetricLength"=>"0,2",
                "volumetricHeight"=>"0,05",
                "weight"=> "0,5"
                ]
            ]
        ];
        if($order->getPayType()->getLinkname()=='c-o-d'){
            $params['BackwardDeliveryData']=(array)[
                0 => (array)[
                    'PayerType' => 'Recipient',
                    'CargoType' => 'Money',
                    'RedeliveryString' => $order->getSumm()
                ]
            ];
        }
        $data = array(
            'apiKey' => $this->apiKey,
            'modelName' => "InternetDocument",
            'calledMethod' => "save",
            'methodProperties' => $params
        );
        $post = json_encode($data);
        $ch = curl_init("https://api.novaposhta.ua/v2.0/json/");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $result = curl_exec($ch);
        curl_close($ch);
        $result=json_decode($result,1);
        if(!isset($result['data'][0]["Ref"])||!isset($result['data'][0]["IntDocNumber"])){
            return false;
        }
        $arr=(object)[
            'ref'=>$result['data'][0]["Ref"],
            'id'=>$result['data'][0]["IntDocNumber"],
        ];
        return $arr;

    }

    /**
     * returnes pdf documents for invoices
     *
     * @param array $refs
     * @return mixed
     */
    public function printInvoices(array $refs)
    {
        $params = (array)[
            'Type'=>'pdf',
            'DocumentRefs'=>$refs
        ];
        $data = array(
            'apiKey' => $this->apiKey,
            'modelName' => "InternetDocument",
            'calledMethod' => "printDocument",
            'language' => "ru",
            'methodProperties' => $params
        );
        $post = json_encode($data);
        $ch = curl_init("https://api.novaposhta.ua/v2.0/json/");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    /**
     * method for Invoice removing
     *
     * @param array $refs
     */
    public function deleteInvoice(array $refs){
        $params = (array)[
            'DocumentRefs'=>$refs
        ];
        $data = array(
            'apiKey' => $this->apiKey,
            'modelName' => "InternetDocument",
            'calledMethod' => "delete",
            'language' => "ru",
            'methodProperties' => $params
        );
        $post = json_encode($data);
        $ch = curl_init("https://api.novaposhta.ua/v2.0/json/");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $result = curl_exec($ch);
        curl_close($ch);
        return;
    }
    private function createRecipient(Order $order)
    {
        $newPostObject = $order->getNovaPoshtaData();
        $name = explode(' ',$order->getName());
        if(!is_array($name)){
            $arr=array($name);
            $name=$arr;
        }
        if(!isset($name[1])){
            $name[1]='Неизвестно';
        }
        if(!$order->getEmail()){
            $email="info@zorrov.com";
        }
        else{
            $email=$order->getEmail();
        }
        $params=(array)[
            'CityRef'=>$newPostObject->getCityHref(),
            'FirstName'=>$name[0],
            'LastName'=>$name[1],
            'MiddleName'=>'',
            'Phone'=>$this->preparePhone($order->getTelephone()),
            'Email'=>$email,
            'CounterpartyType'=>'PrivatePerson',
            'CounterpartyProperty'=>'Recipient'
        ];
        $data = array(
            'apiKey' => $this->apiKey,
            'modelName' => "Counterparty",
            'calledMethod' => "save",
            'language' => "ru",
            'methodProperties' => $params
        );
        $post = json_encode($data);
        $ch = curl_init("https://api.novaposhta.ua/v2.0/json/");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $result = curl_exec($ch);
        curl_close($ch);
        $recipient = json_decode($result,1);
        return (object)[
            'Ref'=>$recipient['data'][0]['Ref'],
            'ContactRecipient'=>$recipient['data'][0]['ContactPerson']['data'][0]['Ref'],

        ];
    }
    private function checkRecipient(Order $order)
    {
        $recipient = $order->getNovaPoshtaData();
        if(!$recipient){
            return false;
        }
        if(
            $recipient->getCityHref()&&
            $recipient->getCityHref()&&
            $recipient->getWarehouseHref()&&
            $order->getName()&&
            $order->getSumm()&&
            $order->getTelephone()
        ){
            return true;
        }
            return false;
    }

    /**
     * get Sender predefined data
     *
     * @return bool|object
     * @throws \Throwable
     */
    private function getSender()
    {
        return (object)[
            'CitySenderRef'=>'db5c88f0-391c-11dd-90d9-001a92567626',
            'SenderRef'=>'b563e874-9a42-11e4-acce-0050568002cf',
            'SenderWarehouseAdressRef'=>'29d27fc7-9a42-11e4-acce-0050568002cf',
            'ContactSender'=>'00477be2-1d54-11e4-acce-0050568002cf',
            'SendersPhone'=>'380971370996'
        ];
    }
    private function preparePhone($str)
    {
        $str=substr($str,4,strlen($str));
        $str=str_replace('(','',$str);
        $str=str_replace(')','',$str);
        $str=str_replace(' ','',$str);
        $str=str_replace('-','',$str);
        return $str;
    }
}