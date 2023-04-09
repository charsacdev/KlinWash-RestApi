@component('mail::panel')
Password Update Confirmation
@endcomponent
@component('mail::message')
<p>Dear&nbsp;<b>{{ucwords($username)}},</b> </p>

<p>We are writing to confirm that your password has been updated for your account with our laundry company.</p>
 
<p>If you did not request the password change, please contact us immediately as this may indicate a potential security breach. However, 
    if you did request the update, please disregard this message.
</p>
 
<p>To confirm that you were the individual that requested for the update, please reply to this email
     with a simple "yes" or "no". We take our customers' privacy and security seriously, 
     and we want to ensure that unauthorized individuals do not access your account.
</p>
 
<p>If you have any questions or concerns about your account, please do not hesitate to reach out to us.
     We are always here to help.
</p>
 
<p>Thank you for choosing our laundry company, and we look forward to serving you again.</p>

 
Best regards,<br>
<b>{{ucwords($username)}}</b><br>
{{ config('app.name') }}
@endcomponent
