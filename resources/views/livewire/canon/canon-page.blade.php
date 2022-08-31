<div>
    <h1>Палийский канон</h1>

    @foreach($suttas as $sutta)
        <?php
        /** @var \App\Models\Sutta $sutta */
        ?>
        <div class="mb-3">
            <a href="{{$sutta->displaySlug()}}">{{$sutta->displayName()}} {{$sutta->displayPaliTitle()}}</a>
        </div>
    @endforeach
</div>
