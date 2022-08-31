<?php namespace App\Services;
use App\Models\Book;
use App\Models\ContentChunk;
use Storage;

class BookService
{
    /**
     * @var Book
     */
    private $book;

    public function __construct(Book $book)
    {
        $this->book = $book;
    }

    public function get()
    {
    	$this->refresh();
    	return $this->book;
    }

    public function refresh()
    {
    	$this->book->refresh()->load("contents.chunks");
    }

    public function createContentFromFile($title, $lang, $filename)
    {
        /** @var \App\Models\Content $content */
        $content = $this->book->contents()->create(['lang'=>$lang, 'title'=>$title]);
        $contentService = new \App\Services\ContentService($content);

        $fileContent = Storage::disk("source_books")->get($filename);
        $fileContent = str_replace("\r\n", "\n", $fileContent);
        $fileArray = explode("\n\n", $fileContent);
        $order = 10;
        foreach($fileArray as $text){
            $contentService->addChunk($text, $order);
            $order = $order + 10;
        }

        return $contentService->getContent();
    }

}
