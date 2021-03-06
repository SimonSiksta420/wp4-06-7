<?php

namespace App\Model;

use Nette;
use stdClass;

final class PostFacade
{
	use Nette\SmartObject;

	private Nette\Database\Explorer $database;

	public function __construct(Nette\Database\Explorer $database)
	{
		$this->database = $database;
	}

	public function getPublicArticles()
	{
		return $this->database
			->table('posts')
			->where('created_at < ', new \DateTime)
			->order('created_at DESC');
	}

	public function getPostById(int $postId)
	{
		$post = $this->database
			->table('posts')
			->get($postId);

		if (!$post) {
			$this->error('Post not found');
		}
		return $post;
	}

	public function addComment(int $postId, \stdClass $data)
	{

		$this->database->table('comments')->insert([
			'post_id' => $postId,
			'name' => $data->name,
			'email' => $data->email,
			'content' => $data->content,
		]);
		return;
	}

	public function getComments(int $postId)
	{
		return $this->database
		->table('comments')
		->where('post_id', $postId);
		
	}

	public function editPost(int $postId, array $data)
	{
		$post = $this->database
				->table('posts')
				->get($postId);
			$post->update($data);
		return $post;
	}

	public function insertPost(array $data)
	{
		$post = $this->database
		->table('posts')
		->insert($data);
		return $post;
	}
}
