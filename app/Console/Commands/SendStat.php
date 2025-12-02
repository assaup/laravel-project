<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Comment;
use App\Models\Click;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\StatMail;

class SendStat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-stat';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info('Запуск команды отправки статистики');
        
        // Правильный подсчет просмотров по статьям
        $article_counts = Click::selectRaw('article_id, article_title, COUNT(*) as count')
            ->groupBy('article_id', 'article_title')
            ->get()
            ->toArray();

        Log::info('Статистика по статьям:', $article_counts);
        Log::info('Количество записей в clicks: ' . Click::count());
        // Удаляем старые записи после отправки
        Click::whereNotNull('article_id')->delete();
        
        $comments = Comment::whereDate('created_at', Carbon::today())->count();
        Log::info('Количество комментариев за сегодня: ' . $comments);

        try {
            Mail::to('samirchiq1@mail.ru')->send(new StatMail($comments, $article_counts));
            Log::info('Письмо отправлено успешно');
        } catch (\Exception $e) {
            Log::error('Ошибка отправки письма: ' . $e->getMessage());
        }
        
        $this->info('Статистика отправлена успешно!');
    }
}