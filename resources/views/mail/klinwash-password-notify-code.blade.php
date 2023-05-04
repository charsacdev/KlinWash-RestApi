@component('mail::message')

<div class="email-div">
  <h1>Password Update Confirmation</h1>

  <aside class="aside-1">
    <img src="{{asset('assets/emailicon.png')}}" class="emailicon">
    <br>
    <p>Hi,&nbsp;<b>{{ucwords($username)}}</b><br>
       We confirm that your password has been updated for your account with our laundry company. 
       If you did not initiate the change, 
       please notify us immediately as it may indicate a potential security breach. 
       To confirm your authorization, kindly reply to this email with a simple "yes" or "no." 
       If you have any questions or concerns about your account, we are available to assist you.
    </p>
     <p style="text-align: center">
        We look forward to serving you.Best regards,
    </p>

  </aside>

  <aside class="aside-2">
     <h4>Get the KlinWash app!</h4>
     <p>Get the most of <b>klnwash by</b> installing the
      mobile app. You can log in by using your
      existing email and password
     </p>
     <article>
       <img src="{{asset('assets/apple.png')}}">
       <img src="{{asset('assets/google.png')}}">
     </article>
  </aside>
  
  <aside class="aside-3">
    <article>
      <img src="{{asset('assets/linkedin.png')}}">
      <img src="{{asset('assets/twitter.png')}}">
      <img src="{{asset('assets/facebook.png')}}">
    </article>
  </aside>

  <aside class="aside-4">
      <img src="{{asset('assets/Vector.png')}}">
      <br>
      <h4>{{ env('APP_BUSINESS') }}</h4>
  </aside>
</div>
@endcomponent
