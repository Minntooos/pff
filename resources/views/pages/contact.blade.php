

<html>
    <head>
    <title>Contact Us - TrackSave</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrackSave - Learning Resource Management</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    @vite('resources/css/app.css')

    <style>

    
     
      main {
        max-width: 800px;
        margin: 40px auto;
        padding: 0 20px;
      }
      h1 {
        color: #1e3799;
        text-align: center;
        margin-bottom: 2rem;
      }
      .contact-container {
        display: flex;
        justify-content: space-between;
        gap: 2rem;
      }
      .contact-form, .contact-info {
        flex: 1;
        background-color: white;
        padding: 2rem;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
      }
      .form-group {
        margin-bottom: 1rem;
      }
      label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: bold;
      }
      input, textarea, select {
        width: 100%;
        padding: 0.5rem;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 1rem;
      }
      textarea {
        height: 150px;
      }
      .btn {
        display: inline-block;
        background-color: #1e3799;
        color: white;
        padding: 0.75rem 1.5rem;
        text-decoration: none;
        border-radius: 5px;
        font-weight: bold;
        transition: background-color 0.3s ease;
        border: none;
        cursor: pointer;
      }
      .btn:hover {
        background-color: #0c2461;
      }
      .contact-info h2 {
        color: #1e3799;
        margin-top: 0;
      }
      .contact-info p {
        margin-bottom: 1rem;
      }
      .social-links {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
      }
      .social-links a {
        color: #1e3799;
        font-size: 1.5rem;
        transition: color 0.3s ease;
      }
      .social-links a:hover {
        color: #0c2461;
      }
      .header {
            position: fixed;
            width: 100%;
            top: 0;
            left: 0;
            z-index: 1000;
            transition: transform 0.3s ease-in-out;
        }
    </style>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    </head>
    <body>
        <header class="header fixed w-full z-20 transition-all duration-300" id="header">
            <div class="container mx-auto flex justify-between items-center p-4 bg-gradient-to-r from-gray-800 to-gray-700 text-white">
                <a href="{{url('/')}}" class="logo flex items-center text-white text-xl font-bold">
                    <img src="{{asset('images/logo.png')}}" alt="TrackSave Logo" class="h-8 mr-2">
                    TrackSave
                </a>
                <nav class="flex gap-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded hover:bg-orange-500 transition duration-300">Dashboard</a>
                    @else
                        <a href="{{ url('/') }}" class="px-4 py-2 rounded hover:bg-orange-500 transition duration-300">Home</a>
                    @endauth
                    <a href="{{ route('resources.index') }}" class="px-4 py-2 rounded hover:bg-orange-500 transition duration-300">Resources</a>
                    <a href="{{ route('contact') }}" class="px-4 py-2 rounded hover:bg-orange-500 transition duration-300">Contact Us</a>
                    <a href="{{ route('terms') }}" class="px-4 py-2 rounded hover:bg-orange-500 transition duration-300">Terms of Service</a>
                </nav>
                @auth
                   <div class="user-profile flex items-center gap-2 cursor-pointer">
            <div class="user-avatar w-8 h-8 rounded-full bg-gray-500 overflow-hidden">
                <img src="{{ Auth::check() ? (Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : asset('images/default-avatar.png')) : 'https://static-00.iconduck.com/assets.00/avatar-default-symbolic-icon-479x512-n8sg74wg.png' }}" alt="{{ Auth::check() ? Auth::user()->name : 'Guest' }}" class="w-full h-full object-cover">
            </div>
            <span>{{ Auth::user()->name }}</span>
        </div>
    @else
        <div class="flex gap-4">
            <a href="{{ route('login') }}" class="px-4 py-2 rounded hover:bg-orange-500 transition duration-300">Login</a>
            <a href="{{ route('register') }}" class="px-4 py-2 rounded hover:bg-orange-500 transition duration-300">Sign Up</a>
        </div>
    @endauth
            </div>
        </header>
    
      <main>
        <h1>Contact Us</h1>
        <div class="contact-container">
          <div class="contact-form">
            <form action="/submit-contact" method="POST">
              <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
              </div>
              <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
              </div>
              <div class="form-group">
                <label for="subject">Subject:</label>
                <select id="subject" name="subject" required>
                  <option value="">Select a subject</option>
                  <option value="general">General Inquiry</option>
                  <option value="support">Technical Support</option>
                  <option value="billing">Billing Question</option>
                  <option value="partnership">Partnership Opportunity</option>
                </select>
              </div>
              <div class="form-group">
                <label for="message">Message:</label>
                <textarea id="message" name="message" required></textarea>
              </div>
              <button type="submit" class="btn">Send Message</button>
            </form>
          </div>
          <div class="contact-info">
            <h2>Get in Touch</h2>
            <p><strong>Phone:</strong><br>+216 XX XXX XXX</p>
            <p><strong>Email:</strong><br><a href="/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="34474144445b464074404655575f475542511a555d">[email&#160;protected]</a></p>
            <p><strong>Hours:</strong><br>Monday - Friday: 9am - 6pm PST</p>
            <div class="social-links">
              <a href="https://facebook.com/tracksave" target="_blank"><i class="fab fa-facebook"></i></a>
              <a href="https://twitter.com/tracksave" target="_blank"><i class="fab fa-twitter"></i></a>
              <a href="https://linkedin.com/company/tracksave" target="_blank"><i class="fab fa-linkedin"></i></a>
              <a href="https://instagram.com/tracksave" target="_blank"><i class="fab fa-instagram"></i></a>
            </div>
          </div>
        </div>
      </main>
    
      <script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script><script>
        document.querySelector('form').addEventListener('submit', function(e) {
          e.preventDefault();
          const name = document.getElementById('name').value;
          const email = document.getElementById('email').value;
          const subject = document.getElementById('subject').value;
          const message = document.getElementById('message').value;
          
          // In a real application, you would send this data to a server
          console.log('Form submitted:', { name, email, subject, message });
          
          alert('Thank you for your message. We will get back to you soon!');
          this.reset();
        });
      </script>
    </body></html>