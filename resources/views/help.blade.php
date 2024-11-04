@extends('layouts.app')

@section('body')
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
<div class="container">
  <div class="top-container">
    <h2 class="font-weight-bold mb-0">
      <i class="fas fa-question-circle"></i> 
      Frequently Asked Questions
    </h2>
  </div> 

  <div class="faq-email-wrapper" style="margin-top: 40px;">
    <div class="faq-container custom-faq">
      <div class="accordion">
          <div class="accordion-item">
      <button class="accordion-button" id="accordion-button-2" aria-expanded="false">
        <span class="accordion-title">What Is "Stelle"?</span>
        <span class="icon" aria-hidden="true"></span>
      </button>
      <div class="accordion-content">
        <p>Stelle is an event management system designed to streamline the planning and execution of conferences and events. The name Stelle, meaning 'star' in French, reflects our commitment to delivering events with efficiency and precise tools.</p>
      </div>
    </div>
        <hr class="divider">
        <div class="accordion-item">
  <button class="accordion-button" id="accordion-button-3" aria-expanded="false">
    <span class="accordion-title">How can I obtain Certificates?</span>
    <span class="icon" aria-hidden="true"></span>
  </button>
  <div class="accordion-content">
    <p>You can claim a certificate by attending events. Depending on the event creator's requirements, an evaluation form may or may not be necessary. The event creator is responsible for distributing the certificates to the participants after the event concludes.</p>
  </div>
  </div>

        <hr class="divider">
        <div class="accordion-item">
      <button class="accordion-button" id="accordion-button-2" aria-expanded="false">
        <span class="accordion-title">Can I Create My Own Event?</span>
        <span class="icon" aria-hidden="true"></span>
      </button>
      <div class="accordion-content">
        <p>Yes, you can request admin privileges to create your own events. Once your request is approved, you will have the ability to not only create events but also manage associated features such as certificates and evaluation forms.</p>
      </div>
    </div>

    <hr class="divider">
<div class="accordion-item">
  <button class="accordion-button" id="accordion-button-2" aria-expanded="false">
    <span class="accordion-title">What Does Events with a "(Deleted)" Tag Mean?</span>
    <span class="icon" aria-hidden="true"></span>
  </button>
  <div class="accordion-content">
    <p>Events labeled with a "(Deleted)" tag indicates that the event was removed by either the event creator or an administrator. Unlike closed/finished events, which can still be seen in the event list page, deleted events will not be visible to anyone unless they are either the creator or an attendee of the now Deleted event.</p>
  </div>
</div>

         
  </div>
</div>

    <div class="email-container custom-email">
      <h3 class="email-header">
        <i class="fas fa-envelope"></i> Email Us
      </h3>
      <p>If you have a more specific question or encounter any issues, feel free to reach out:</p>
      <hr class="email-divider">
      <p class="email-text"><strong>stelle.psite@gmail.com</strong></p>
      <hr class="email-divider">
    </div>
  </div>
</div>

<style>
body {
  font-family: 'Hind', sans-serif;
  background: #e8f5e9;
  margin: 0;
  padding: 0;
  color: #003d80; /* Set default text color */
}

.top-container {
  text-align: center;
  padding: 2rem;
}

.faq-email-wrapper {
  display: flex;
  justify-content: center;
  align-items: stretch;
  margin: 40px auto 0;
  gap: 1rem;
  width: 100%;
}

.faq-container, .email-container {
  background-color: white;
  border-radius: 10px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
  padding: 2rem;
  width: 50%;
  max-width: 1000px;
  transition: transform 0.3s ease;
  color: #003d80; /* Text color in all containers */
  margin-bottom: 20px;
}

.faq-container:hover, .email-container:hover {
  transform: translateY(-5px);
}

.email-container {
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}

.email-header, .accordion-title {
  color: #003d80;
  font-weight: bold;
}

.email-text {
  color: #003d80;
  font-weight: normal;
  font-size: 25px;
  text-align: center;
}

.email-divider, .divider {
  border-top: 2px solid #003d80;
  margin: 1rem 0;
}

.accordion-item {
  border-bottom: none;
  margin-bottom: 1rem;
}

.custom-faq .accordion-button,
.custom-faq .accordion-title,
.custom-faq .accordion-content,
.custom-email .email-header,
.custom-email .email-text {
  color: #003d80;
}

.accordion-button {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: none;
  border: none;
  width: 100%;
  padding: 1.5rem;
  text-align: left;
  font-size: 1.5rem;
  font-weight: normal;
  outline: none;
  transition: color 0.3s ease;
  position: relative;
  height: 70px;
  color: #003d80;
}

.accordion-button:hover {
  color: #1abc9c;
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
  color: #003d80;
  font-size: 1.2rem;
}

.accordion-button[aria-expanded='true'] .icon::before {
  content: '-';
}

.accordion-content {
  padding: 1.5rem;
  max-height: 0;
  overflow: hidden;
  opacity: 0;
  transition: max-height 0.3s ease, opacity 0.3s ease;
  color: #003d80;
}

.accordion-button[aria-expanded='true'] + .accordion-content {
  max-height: 15em;
  opacity: 1;
}

@media only screen and (max-width: 768px) {
  .faq-email-wrapper {
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
    color: #003d80;
  }

  .accordion-button {
    font-size: 1.2rem;
    padding: 1rem;
    text-align: left;
  }
}

@media only screen and (max-width: 480px) {
  .faq-container, .email-container {
    width: 90%;
  }

  .accordion-button {
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

/* Additional styles to avoid affecting logout modal */
.logout-modal {
  font-size: 1rem;
  line-height: 1.5;
}
</style>

<script>
const items = document.querySelectorAll(".accordion-button");

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
