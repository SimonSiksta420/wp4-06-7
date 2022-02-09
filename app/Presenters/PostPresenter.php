<?php

namespace App\Presenters;

use App\Model\PostFacade;
use Nette;
use Nette\Application\UI\Form;
use stdClass;

final class PostPresenter extends Nette\Application\UI\Presenter
{
	private Nette\Database\Explorer $database;

	private PostFacade $facade;

	public function __construct(PostFacade $facade)
	{
		$this->facade = $facade;
	}

	public function renderShow(int $postId): void
	{
		$post = $this->facade
			->getPostById($postId);

		$this->template->post = $post;
		$this->template->comments = $post->related('comments')->order('created_at');
	}

	protected function createComponentCommentForm(): Form
	{
		$form = new Form;

		$form->addText('name', 'Jméno:')
			->setRequired();

		$form->addEmail('email', 'E-mail:');

		$form->addTextArea('content', 'Komentář:')
			->setRequired();

		$form->addSubmit('send', 'Publikovat komentář');

		$form->onSuccess[] = [$this, 'commentFormSucceeded'];

		return $form;
	}

	public function commentFormSucceeded(\stdClass $data): void
	{
		$postId = $this->getParameter('postId'); 
				  $this->facade
				->getComments(int $postId, \stdClass $data); 

		$this->flashMessage('Děkuji za komentář', 'success');
		$this->redirect('this');
	}
}
