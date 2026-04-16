

<html lang="en"><head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Internship Application &amp; Tracking System</title>
  <style>
    * {
      margin: 0;
      /* padding: 0;*/
      box-sizing: border-box;
      font-family: 'Segoe UI', sans-serif;
    }
    section {
      display: block;
      unicode-bidi: isolate;
    }
    body {
      line-height: 1.6;
      background: #ffffff;
      color: #333;
	  background-image:url('/intern-management/img/home2.jpeg');
	  /*background-size: cover;*/
	  background-position: center;
	  background-position-x: center;
    	  background-position-y: center;
	  background-attachment: fixed;
	  background-repeat: no-repeat;
          min-height: 100vh;
    }
    header {
      background: linear-gradient(90deg, #a52496, #7a70ef);
      color: #333;
      padding: 20px 50px;
      /* text-align: center; */
    	position: fixed;
    	width: 100%;
	height: 13vh;
	z-index: 9999;
	transform: none;
	backdrop-filter: blur(10px);
    }
    header h1 {
      font-size: 2.1rem;
      color: #571794;
    }
    nav {
      float: inline-end;
      margin-top: 10px;
    }
#spacer {
	height: 13vh;
	padding: 20px 50px;
}
    nav a {
      color: #2d3a8c;
      text-decoration: none;
      margin: 0 15px;
      font-weight: bold;
      transition: color 0.3s;
    }
    nav a:hover {
      color: #5a67d8;
    }
    .hero {
      align-items: center;
      text-align: center;
      height: 87vh;
      background: linear-gradient(rgba(250, 254, 255, 0.5), rgba(166, 200, 255, 0.4)), url(https://source.unsplash.com/1600x900/?students,study) no-repeat center center / cover;
      color: #1fd0d7e3;
      padding: 20px;
      padding-top:30vh;
    }
    .hero h2 {
      font-size: 2.5rem;
      margin-bottom: 15px;
    }
    .hero p {
      font-size: 1.2rem;
      margin-bottom: 20px;
      color: #1a202c;
    }
    .btn {
      background: #de2ca2;
      color: white;
      padding: 12px 25px;
      border: none;
      cursor: pointer;
      font-size: 1rem;
      margin: 5px;
      border-radius: 30px;
      transition: background 0.3s ease, transform 0.2s;
    }
    .btn:hover {
      background: #5a67d8;
      transform: scale(1.05);
    }
    .section {
      padding: 8.5em;
      text-align: center;
    }
    .features {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      margin-top: 20px;
    }
    .card {
      background: #ffffff;
	position: relative;
      margin: 15px;
      padding: 25px;
      border-radius: 15px;
      box-shadow: 0 6px 12px rgba(174, 197, 255, 0.3);
      border: 1px solid #d1d9ff;
      flex: 1 1 280px;
      max-width: 300px;
	z-index: none;
      transition: transform 0.3s;
    }
    .card:hover {
      transform: translateY(-5px);
    }
    .card img {
      width: 80px;
      margin-bottom: 15px;
    }
    .card h3 {
      margin-bottom: 10px;
      color: #2d3a8c;
    }
    footer {
      background: #a6c8ff;
      color: #2d3a8c;
      padding: 20px;
      text-align: center;
      margin-top: 30px;
    }
    .contact-form {
      max-width: 500px;
      margin: 0 auto;
      text-align: left;
    }
    .contact-form input, .contact-form textarea {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border-radius: 8px;
      border: 1px solid #c4c7f5;
      font-size: 1rem;
    }
    .contact-form button {
      width: 100%;
    }
  </style>
</head>
<body>
  <header>
    <h1 style="
    display: contents;
">DreamJob</h1>
    <nav style="
    display: inline;
">
      <a href="#home">Home</a>
      <a href="#features">Features</a>
      <a href="#about">About</a>
      <a href="#contact">Contact</a>
    </nav>
  </header>
<div id="spacer"></div>
  <section class="hero" id="home">
    <h2>Apply, Track &amp; Manage Internships with Ease</h2>
    <p>Your one-stop solution to manage your internship applications and progress.</p>
    <button class="btn" fdprocessedid="9iln2c">Get Started</button>
    <button class="btn" fdprocessedid="6f7luo">Login</button>
  </section>

  <section class="section" id="features">
    <h2>Our Features</h2>
    <div class="features">
      <div class="card">
        <img src="https://cdn-icons-png.flaticon.com/512/3135/3135755.png" alt="Apply">
        <h3>Apply</h3>
        <p>Easily submit internship applications through our simple interface.</p>
      </div>
      <div class="card">
        <img src="https://cdn-icons-png.flaticon.com/512/4305/4305693.png" alt="Track">
        <h3>Track</h3>
        <p>Monitor your application status and receive real-time updates.</p>
      </div>
      <div class="card">
        <img src="https://cdn-icons-png.flaticon.com/512/2991/2991148.png" alt="Manage">
        <h3>Manage</h3>
        <p>Keep all your internship details organized in one place.</p>
      </div>
    </div>
  </section>

  <section class="section" id="about">
    <h2>About Us</h2>
    <p>We built this platform to help students apply for internships and track their progress seamlessly. Our goal is to bridge the gap between students and organizations by providing a user-friendly, transparent system.</p>
  </section>

  <section class="section" id="contact">
    <h2>Contact Us</h2>
    <div class="contact-form">
      <form>
        <input type="text" placeholder="Your Name" required="" fdprocessedid="ye10l7h">
        <input type="email" placeholder="Your Email" required="" fdprocessedid="v7d41">
        <textarea rows="4" placeholder="Your Message"></textarea>
        <button type="submit" class="btn" fdprocessedid="z3vgk9">Send Message</button>
      </form>
    </div>
  </section>

  <footer>
    <p>© 2025 Internship Application &amp; Tracking System. All Rights Reserved.</p>
  </footer>


<span id="PING_IFRAME_FORM_DETECTION" style="display: none;"></span></body></html>