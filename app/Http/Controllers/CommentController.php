<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\User;
use App\Models\Article;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;
use App\Jobs\VeryLongJob;
use App\Notifications\NewCommentNotify;

class CommentController extends Controller
{
    public function index(){
        $page = (isset($_GET['page'])) ? $_GET["page"] : 0;
        $comments = Cache::rememberForever('comments_'.$page, function(){
        return Comment::latest()->paginate(10);
        });
        return view('comment.index', ['comments'=>$comments]);
    }

    public function store(Request $request){
        $request->validate([
            'text'=>'min:10|required',
        ]);
        $article = Article::FindOrFail($request->article_id);
        $comment = new Comment;
        $comment-> text = $request->text;
        $comment->article_id = $request->article_id;
        $comment->user_id = auth()->id();
        if($comment->save()){
            VeryLongJob::dispatch($article, $comment, auth()->user()->name);
            $keys = DB::table('cache')->whereRaw('`key` GLOB :key', [':key'=>'comments_*[0-9]'])->get();
            foreach($keys as $param){
                Cache::forget($param->key);
            }
        }
        return redirect()->route('article.show', $request->article_id)->with('message', "Comment add succesful and enter for moderation");

    }

    public function edit(Comment $comment){
        Gate::authorize('comment', $comment);
    }
    public function update(Comment $comment){
        Gate::authorize('comment', $comment);
        if($comment->save()){
            Cache::flush();
        }
        return 0;
    }

    public function delete(Comment $comment){
        Gate::authorize('comment', $comment);
        if($comment->save()){
            Cache::forget('comments'.$comment->article_id);
            $keys = DB::table('cache')->whereRaw('`key` GLOB :key', [':key'=>'comments_*[0-9]'])->get();
            foreach($keys as $param){
                Cache::forget($param->key);
            }
        }
        return 0;
    }
    public function accept(Comment $comment){
        $comment->accept = true;
        $article = Article::findOrFail($comment->article_id);
        $users = User::where('id', '!=', $comment->user_id)->get();
        if($comment->save()){
            Notification::send($users, new NewCommentNotify($article->title, $article->id));
            Cache::flush();
        }
        return redirect()->route('comment.index');
    }

    public function reject(Comment $comment){
        $comment->accept = false;
        $comment->save();
        return redirect()->route('comment.index');
    }
}
