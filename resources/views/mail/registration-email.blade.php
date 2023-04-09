@component('mail::panel')
Registration Successful with KLN Wash
@endcomponent
@component('mail::message')
<p>Dear&nbsp;<b>{{ucwords($username)}}</b> </p>

<p>We are delighted to inform you that your registration with KLN Wash has been successful. Welcome to our family of satisfied customers!</p>
 
<p>At KLN Wash, we take pride in providing exceptional laundry services to our customers. We are dedicated to ensuring that your laundry needs are met with utmost care and attention to detail.</p>
 
<p>As a registered member, you can now enjoy a host of benefits, including convenient pick-up 
  and drop-off services, fast turnaround times, and special discounts on select services. 
  Our team of experts is always available to answer any queries or concerns you may have about our 
  services.
</p>
 
<p>To get started, simply visit our website or call our customer service representatives to 
  schedule a pickup at your preferred location and time. We will take care of the rest!
</p>
 
<p>Thank you for choosing KLN Wash as your preferred laundry service provider. 
  We look forward to serving you.
</p>
 
Best regards,<br>
<b>{{ucwords($username)}}</b><br>
Customer Service Representative,
<br>
{{ config('app.name') }}
@endcomponent
