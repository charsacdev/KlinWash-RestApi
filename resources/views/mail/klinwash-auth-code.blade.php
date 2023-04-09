@component('mail::panel')
KLN Wash Authentication Code
@endcomponent
@component('mail::message')
<p>Dear&nbsp;<b>{{ucwords($username)}}</b> </p>

<p>
Thank you for choosing KLN Wash as your preferred laundry service provider. 
As part of our commitment to ensuring the security of your account, 
we require an authentication code to verify your identity and protect your personal information.
</p>
 
<p>Your authentication code is: <b>{{$code}}</b></p>
 
<p>
To complete the registration process, please enter the authentication code on the designated 
field on our website. If you have any difficulty entering the code, 
please feel free to contact our customer service team for assistance.
</p>
 
<p>
Please note that this authentication code is only valid for a limited time, 
so we encourage you to enter it as soon as possible to avoid any delays in accessing our services.
</p>
 
<p>Thank you for choosing KLN Wash. We look forward to serving you.</p>

 
Best regards,<br>
{{ config('app.name') }}
@endcomponent
