@component('mail::message')
<span style="font-weight: bold">{{$subscriber->email}}</span> just subscribed to the mailing list.
<br><br>
IP: {{$subscriber->IP}}
<br><br>
Two balls in a nutsack, yo<br>
Your Past Self

@component('mail::button', ['url' => 'http://gwiki.io/gigger/admin'])
Log in to whatever portal
@endcomponent

@endcomponent
