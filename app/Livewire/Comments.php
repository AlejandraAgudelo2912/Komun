<?php

namespace App\Livewire;

use App\Models\Comment;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class Comments extends Component
{
    public $comments = [];

    public $requestModel;

    public $showModal = false;

    public $commentBody;

    public $editingCommentId = null;

    protected $rules = [
        'commentBody' => 'required',
    ];

    public function mount($requestModel)
    {
        $this->requestModel = $requestModel;
        $this->loadComments();
    }

    public function loadComments()
    {
        $this->comments = $this->requestModel->comments()->with('user')->get();
    }

    public function createComment()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function editComment($commentId)
    {
        $comment = $this->requestModel->comments()->find($commentId);

        if ($comment) {
            if (Gate::allows('update', $comment)) {
                $this->editingCommentId = $comment->id;
                $this->commentBody = $comment->body;
                $this->showModal = true;
            } else {
                session()->flash('error', 'No tienes permiso para editar este comentario.');
            }
        } else {
            session()->flash('error', 'Comentario no encontrado.');
        }
    }

    public function saveComment()
    {
        $this->validate();

        if ($this->editingCommentId) {
            $this->updateComment();
        } else {
            $this->storeComment();
        }
    }

    public function storeComment()
    {
        if (Gate::allows('create', Comment::class)) {
            $this->requestModel->comments()->create([
                'user_id' => auth()->user()->id,
                'body' => $this->commentBody,
            ]);

            session()->flash('success', 'Comentario creado exitosamente.');
            $this->afterSave();
        } else {
            session()->flash('error', 'No tienes permiso para crear comentarios.');
        }
    }

    public function updateComment()
    {
        $comment = $this->requestModel->comments()->find($this->editingCommentId);

        if ($comment) {
            if (Gate::allows('update', $comment)) {
                $comment->update(['body' => $this->commentBody]);
                session()->flash('success', 'Comentario actualizado exitosamente.');
                $this->afterSave();
            } else {
                session()->flash('error', 'No tienes permiso para actualizar este comentario.');
            }
        } else {
            session()->flash('error', 'Comentario no encontrado.');
        }
    }

    public function deleteComment($commentId)
    {
        $comment = $this->requestModel->comments()->find($commentId);

        if ($comment) {
            if (Gate::allows('delete', $comment)) {
                $comment->delete();
                session()->flash('success', 'Comentario eliminado exitosamente.');
                $this->loadComments();
            } else {
                session()->flash('error', 'No tienes permiso para eliminar este comentario.');
            }
        } else {
            session()->flash('error', 'Comentario no encontrado.');
        }
    }

    public function afterSave()
    {
        $this->resetForm();
        $this->loadComments();
        $this->closeModal();
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function resetForm()
    {
        $this->commentBody = '';
        $this->editingCommentId = null;
    }

    public function render()
    {
        return view('livewire.comments');
    }
}
