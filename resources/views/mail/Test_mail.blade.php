<body id="mail-template">

 	<div class="container yellow">

	   <div class="col-md-12 inner">
	   		<img class="img-responsive" src="http://bmusewebsites.s3.amazonaws.com/test/logo.gif">

	   		<div class="seperator"></div>

	   		<div class="text">

		   		<h4>Hello {{ $data['name'] }},</h4>

		   		<p>
		   			{!! nl2br($data['message']) !!}
		   		</p>

		   		<h5>-From {{ config('app.name') }}</h5>

		   	</div>
	   		<div class="seperator"></div>
	   </div>

	</div>
<style type="text/css">
#mail-template .yellow {
   background-image: url("http://bmusewebsites.s3.amazonaws.com/test/background.jpg");
   color:  #f8f5ec;
   width: 600px;
   align-content: center;
   background-repeat: repeat-y no-repeat;
   background-position: 50% 50%;
   padding-bottom: 1px;
}
#mail-template .inner img {
  width: 100%;
}
#mail-template .inner {
   width:400px;
   margin: 0px 50px 50px 50px;
   padding: 0px 50px 50px 50px;
   background-color: white;
}

#mail-template .text{
  font-family:Georgia, Times, serif;  font-size:14px; color:#333333;
}

#mail-template .seperator{
  border: 2px solid #BDBAB5;
}
</style>

</body>
