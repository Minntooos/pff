<html><head><title>TrackSave - Learning Resources</title>
    <style>
      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: Arial, sans-serif;
      }
      body {
        background-color: #f5f5f5;
        color: #333;
      }
      .header {
        background: linear-gradient(to right, #2c3e50, #34495e);
        padding: 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: sticky;
        top: 0;
        z-index: 1000;
      }
      .logo {
        color: white;
        font-size: 1.5rem;
        font-weight: bold;
        text-decoration: none;
      }
      .nav {
        display: flex;
        gap: 1rem;
      }
      .nav a {
        color: white;
        text-decoration: none;
        padding: 0.5rem 1rem;
        transition: background-color 0.3s;
      }
      .nav a:hover, .nav a.active {
        background-color: rgba(255, 255, 255, 0.1);
        border-radius: 5px;
      }
      .main-content {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 0 1rem;
      }
      .search-bar {
        display: flex;
        margin-bottom: 2rem;
      }
      .search-bar input {
        flex-grow: 1;
        padding: 0.5rem;
        border: 1px solid #ddd;
        border-right: none;
        border-top-left-radius: 5px;
        border-bottom-left-radius: 5px;
      }
      .search-bar button {
        padding: 0.5rem 1rem;
        background-color: #3498db;
        color: white;
        border: none;
        border-top-right-radius: 5px;
        border-bottom-right-radius: 5px;
        cursor: pointer;
      }
      .filters {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
      }
      .filter-dropdown {
        position: relative;
      }
      .filter-dropdown select {
        appearance: none;
        background-color: white;
        border: 1px solid #ddd;
        padding: 0.5rem 2rem 0.5rem 0.5rem;
        border-radius: 5px;
        cursor: pointer;
      }
      .filter-dropdown::after {
        content: '▼';
        font-size: 0.8rem;
        position: absolute;
        right: 0.7rem;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
      }
      .resources-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 2rem;
      }
      .resource-card {
        background-color: white;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        overflow: hidden;
        transition: transform 0.3s, box-shadow 0.3s;
      }
      .resource-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
      }
      .resource-image {
        width: 100%;
        height: 150px;
        object-fit: cover;
      }
      .resource-content {
        padding: 1rem;
      }
      .resource-title {
        font-size: 1.2rem;
        margin-bottom: 0.5rem;
      }
      .resource-description {
        font-size: 0.9rem;
        color: #666;
        margin-bottom: 1rem;
      }
      .resource-meta {
        display: flex;
        justify-content: space-between;
        font-size: 0.8rem;
        color: #888;
      }
      .pagination {
        display: flex;
        justify-content: center;
        margin-top: 2rem;
      }
      .pagination button {
        background-color: #3498db;
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        margin: 0 0.25rem;
        cursor: pointer;
        border-radius: 5px;
      }
      .pagination button:disabled {
        background-color: #ccc;
        cursor: not-allowed;
      }
    </style>
  </head>
  <body>
  
    <main class="main-content">
      <div class="search-bar">
        <input type="text" placeholder="Search resources...">
        <button>Search</button>
      </div>
  
      <div class="filters">
        <div class="filter-dropdown">
          <select>
            <option value="">All Categories</option>
            <option value="programming">Programming</option>
            <option value="design">Design</option>
            <option value="business">Business</option>
            <option value="marketing">Marketing</option>
          </select>
        </div>
        <div class="filter-dropdown">
          <select>
            <option value="">All Types</option>
            <option value="video">Video</option>
            <option value="article">Article</option>
            <option value="course">Course</option>
            <option value="ebook">E-book</option>
          </select>
        </div>
        <div class="filter-dropdown">
          <select>
            <option value="">Sort By</option>
            <option value="date">Date Added</option>
            <option value="popularity">Popularity</option>
            <option value="rating">Rating</option>
          </select>
        </div>
      </div>
  
      <div class="resources-grid">
        <div class="resource-card">
          <img src="https://images.unsplash.com/photo-1461749280684-dccba630e2f6" alt="Coding" class="resource-image">
          <div class="resource-content">
            <h3 class="resource-title">Introduction to Python</h3>
            <p class="resource-description">Learn the basics of Python programming in this comprehensive course.</p>
            <div class="resource-meta">
              <span>Video Course</span>
              <span>4.8 ★</span>
            </div>
          </div>
        </div>
        <div class="resource-card">
          <img src="https://images.unsplash.com/photo-1510915228340-29c85a43dcfe" alt="UX Design" class="resource-image">
          <div class="resource-content">
            <h3 class="resource-title">UX Design Fundamentals</h3>
            <p class="resource-description">Master the principles of user experience design with hands-on projects.</p>
            <div class="resource-meta">
              <span>Interactive Course</span>
              <span>4.6 ★</span>
            </div>
          </div>
        </div>
        <div class="resource-card">
          <img src="https://images.unsplash.com/photo-1454165804606-c3d57bc86b40" alt="Business Strategy" class="resource-image">
          <div class="resource-content">
            <h3 class="resource-title">Business Strategy for Startups</h3>
            <p class="resource-description">Learn how to develop and implement effective business strategies for your startup.</p>
            <div class="resource-meta">
              <span>E-book</span>
              <span>4.5 ★</span>
            </div>
          </div>
        </div>
        <div class="resource-card">
          <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71" alt="Data Science" class="resource-image">
          <div class="resource-content">
            <h3 class="resource-title">Data Science Essentials</h3>
            <p class="resource-description">Dive into the world of data science with this comprehensive guide.</p>
            <div class="resource-meta">
              <span>Video Series</span>
              <span>4.9 ★</span>
            </div>
          </div>
        </div>
        <div class="resource-card">
          <img src="https://images.unsplash.com/photo-1522542550221-31fd19575a2d" alt="Digital Marketing" class="resource-image">
          <div class="resource-content">
            <h3 class="resource-title">Digital Marketing Mastery</h3>
            <p class="resource-description">Learn the latest digital marketing techniques to grow your online presence.</p>
            <div class="resource-meta">
              <span>Online Course</span>
              <span>4.7 ★</span>
            </div>
          </div>
        </div>
        <div class="resource-card">
          <img src="https://images.unsplash.com/photo-1555949963-ff9fe0c870eb" alt="Mobile App Development" class="resource-image">
          <div class="resource-content">
            <h3 class="resource-title">Mobile App Development with React Native</h3>
            <p class="resource-description">Build cross-platform mobile apps using React Native framework.</p>
            <div class="resource-meta">
              <span>Video Tutorial</span>
              <span>4.8 ★</span>
            </div>
          </div>
        </div>
      </div>
  
      <div class="pagination">
        <button disabled>Previous</button>
        <button>1</button>
        <button>2</button>
        <button>3</button>
        <button>Next</button>
      </div>
    </main>
  </body></html>