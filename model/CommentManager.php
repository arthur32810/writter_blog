<?php
namespace Arthur\WriterBlog\Model;

require_once('model/Manager.php');

class CommentManager extends Manager
{
	public function pagingComments($postId){
		$db = Manager::dbConnect();

		$paging = $db->prepare('SELECT COUNT(*) AS nb_comments FROM comments WHERE post_id = ?');
		$paging->execute(array($postId));

		$data = $paging->fetch();
	    $nb_comments = $data['nb_comments']; // retourne le nombre d'entrée

	    if($nb_comments == 0){ $nb_paging_comments = $nb_comments;}
	    elseif($nb_comments >=1){
	    	$nb_paging_comments = (int) ($nb_comments / 5); // divise par 5; 
	    	$nb_paging_comments++;
	    }

	    return $nb_paging_comments;
	}

	public function getComments($postId, $limit1, $limit2)
	{
		$db = Manager::dbConnect();
		
		$comments = $db->prepare('SELECT id, user_id, author, comment, DATE_FORMAT(comment_date, \'%d/%m/%Y à %Hh%imin\') 
										AS comment_date_fr FROM comments WHERE post_id = ? ORDER BY comment_date LIMIT '.$limit1.','.$limit2.'');
		$comments->execute(array($postId));			
		
		return $comments;
	}

	public function getComment($id)
	{
		$db = Manager::dbConnect();
		
		$comment = $db->prepare('SELECT id, user_id, author, comment, DATE_FORMAT(comment_date, \'%d/%m/%Y à %Hh%imin\') 
										AS comment_date_fr FROM comments WHERE id = ?');
		$comment->execute(array($id));

		$comment = $comment->fetch();			
		
		return $comment;
	}

	public function addComment($postId, $userId, $author, $comment)
	{
		$db = Manager::dbConnect();
		
		$comments = $db->prepare('INSERT INTO comments (post_id, user_id, author, comment, comment_date) VALUES (?, ?, ?, ?, NOW())');
		$affectedLines = $comments->execute(array($postId, $userId, $author, $comment));
		
		return $affectedLines;
	}

	public function updateComment($id, $comment){
		$db = Manager::dbConnect();

		$updateComment = $db->prepare('UPDATE comments SET comment = :comment, update_date=NOW() WHERE id =:id');
		$updateComment->execute(array('comment'=>$comment,
										'id' => $id));

		return $updateComment;
	}

	public function deleteComment($id){
		$db = Manager::dbConnect();

		$deleteComment = $db->prepare('DELETE FROM comments WHERE id=?');
		$deleteComment->execute(array($id));

		return $deleteComment;
	}

	public function deleteCommentChapter($id){
		$db = Manager::dbConnect();

		$deleteComment = $db->prepare('DELETE FROM comments WHERE post_id=?');
		$deleteComment->execute(array($id));

		return $deleteComment;
	}

	public function deleteCommentModeration($id){
		$db = Manager::dbConnect();

		$deleteCommentModeration = $db->prepare('DELETE FROM moderation WHERE id_comment = ?');
		$deleteCommentModeration->execute(array($id));

		return $deleteCommentModeration;
	}
}