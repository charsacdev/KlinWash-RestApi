@component('mail::message')

<div class="email-div">
  <h1>KLN Wash Authentication Code</h1>

  <aside class="aside-1">
    <img src="{{asset('assets/emailicon.png')}}" class="emailicon">
    <br>
    <p>Hi,&nbsp;<b>{{ucwords($username)}}</b><br>
        We prioritize your account's security and require an authentication code to safeguard your personal data. Kindly input the provided authentication code to finish the registration process.
    </p>
    <h2 style="text-align: center;font-size:30px">
        [{{$code}}]
    </h2>
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
