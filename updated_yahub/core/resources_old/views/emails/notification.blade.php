@component('mail::message') <h1> <?php echo $text;?> </h1> @component('mail::button', ['url' => $url]) View @endcomponent Thanks,<br> {{ config('app.name') }} @endcomponent
