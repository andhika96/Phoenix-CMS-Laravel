<div>
   Hello World! Hehehehehe
</div>

@foreach ($blog_articles as $blog_article)
{{ $loop->index }} - {{ $blog_article->title }} <br/>

@endforeach