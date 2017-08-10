<?php
namespace Site\BackendBundle\Twig\Extension;
use Site\BackendBundle\Entity\Comment;

class CommentStateExtension extends \Twig_Extension
{
    public function getName()
    {
        return 'comment_state.extension';
    }
    public function getFilters() {
        return [
            'comment_state'   => new \Twig_SimpleFilter('comment_state', array($this, 'commentState')),
        ];
    }
    public function commentState($str) {
        $comment = new Comment();
        $states = array_flip($comment->states);
        $str = mb_strtolower(str_replace(' ', '', $str),'UTF-8');
        $result='';
        if(isset($states[$str])){
            $result = "<span class='state-field ".$str."'>".$states[$str]."</span>";
        }
        return $result;
    }
}