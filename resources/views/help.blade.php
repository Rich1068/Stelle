@extends('layouts.app')

@section('body')

<div class="container">
  <div class="top-container">
    <h2 class="font-weight-bold mb-0">
      <i class="fas fa-question-circle"></i> 
      Frequently Asked Questions
    </h2>
  </div> 

  <div class="faq-email-container">
    <div class="faq-container">
      <div class="accordion">
        <div class="accordion-item">
          <button id="accordion-button-1" aria-expanded="false">
            <span class="accordion-title">Why is the moon sometimes out during the day?</span>
            <span class="icon" aria-hidden="true"></span>
          </button>
          <div class="accordion-content">
            <p>Lorem ipsum dolor sit amet...</p>
          </div>
        </div>
        <div class="accordion-item">
          <button id="accordion-button-2" aria-expanded="false">
            <span class="accordion-title">Why is the sky blue?</span>
            <span class="icon" aria-hidden="true"></span>
          </button>
          <div class="accordion-content">
            <p>Lorem ipsum dolor sit amet...</p>
          </div>
        </div>
      </div>
    </div>

    <div class="email-container">
      <h3 class="email-header">
        <i class="fas fa-envelope"></i> Email Us
      </h3>
      <p>If you have a more specific question or encounter any issues, feel free to reach out:</p>
      <hr class="email-divider">
      <p class="email-text"><strong>stelle.psite@gmail.com</strong></p>
    </div>
  </div>
</div>

<style>
body {
  font-family: 'Hind', sans-serif;
  background: #f3f3f3;
  margin: 0;
  padding: 0;
  color: #333;
}

.top-container {
  text-align: center;
  padding: 2rem;
}

.faq-email-container {
  display: flex;
  justify-content: space-between;
  align-items: stretch;
  margin: 2rem auto;
  gap: 1rem;
  width: 80%;
}

.faq-container {
  background-color: white;
  border-radius: 15px;
  box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
  padding: 2rem;
  width: 60%;
  max-width: 900px;
}

.email-container {
  background-color: white;
  border-radius: 15px;
  box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
  padding: 2rem;
  width: 30%;
  max-width: 300px;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}

.email-header, .accordion-title {
  color: darkblue;
  font-weight: bold;
}

.email-text {
  color: darkblue;
  font-weight: bold;
}

.email-divider {
  border-top: 2px solid darkblue;
  margin: 1rem 0;
}

.accordion-item {
  border-bottom: none;
  margin-bottom: 1rem;
}

button {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: none;
  border: none;
  width: 100%;
  padding: 1.5rem;
  text-align: left;
  font-size: 1.5rem;
  font-weight: bold;
  color: darkblue;
  outline: none;
  transition: color 0.3s ease;
  position: relative;
  height: 70px;
}

button:hover {
  color: #00008b;
}

.icon {
  width: 24px;
  height: 24px;
  position: absolute;
  bottom: 10px;
  right: 10px;
}

.icon::before {
  content: '+';
  color: darkblue;
  font-size: 1.2rem;
}

button[aria-expanded='true'] .icon::before {
  content: '-';
}

.accordion-content {
  padding: 1.5rem;
  max-height: 0;
  overflow: hidden;
  opacity: 0;
  transition: max-height 0.3s ease, opacity 0.3s ease;
  color: darkblue;
}

button[aria-expanded='true'] + .accordion-content {
  max-height: 15em;
  opacity: 1;
}

@media only screen and (max-width: 768px) {
  .faq-email-container {
    flex-direction: column;
    align-items: center;
  }

  .faq-container, .email-container {
    width: 80%;
    margin: 0;
    margin-bottom: 2rem;
  }

  .accordion-title, .accordion-content p, .email-text {
    font-size: 1rem;
    overflow-wrap: break-word;
    text-align: left;
    margin-right: 20px;
  }

  button {
    font-size: 1.2rem;
    padding: 1rem;
    text-align: left;
  
  }
}

@media only screen and (max-width: 480px) {
  .faq-container {
    width: 80%;
  }

  button {
    font-size: 1rem;
    padding: 0.8rem;
  }

  .icon {
    width: 20px;
    height: 20px;
    bottom: 5px;
    right: 5px;
  }
}
</style>

<script>
const items = document.querySelectorAll(".accordion button");

function toggleAccordion() {
  const itemToggle = this.getAttribute('aria-expanded');
  
  for (let i = 0; i < items.length; i++) {
    items[i].setAttribute('aria-expanded', 'false');
  }
  
  if (itemToggle === 'false') {
    this.setAttribute('aria-expanded', 'true');
  }
}

items.forEach(item => item.addEventListener('click', toggleAccordion));
</script>

@endsection
