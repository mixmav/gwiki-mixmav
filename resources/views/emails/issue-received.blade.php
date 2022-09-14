@component('mail::message')
Someone submitted an issue.
<br><br>

Name: {{$issue->name}}
<br>
Email: {{$issue->email}}
<br>
Link: {{$issue->link}}
<br>
Message: {{$issue->message}}
<br><br>
IP: {{$issue->IP}}

@component('mail::button', ['url' => 'http://gwiki.io/gigger/admin'])
Log in to whatever portal
@endcomponent

Yeah science, bitch<br>
Your past self
@endcomponent
