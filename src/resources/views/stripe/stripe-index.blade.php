<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>Payment</title>

    <link rel="stylesheet" href="{{ asset('/css/stripe/base.css') }}" />
    <script src="https://js.stripe.com/v3/"></script>

    <script src="/utils.js" defer></script>
    <script src="/index.js" defer></script>
  </head>
  <body>
    <main>
      <h1>Payment</h1>

      <p>Enable more payment method types <a
        href="https://dashboard.stripe.com/settings/payment_methods" target="_blank">in your
        dashboard</a>.</p>

      <form id="payment-form">
        <div id="link-authentication-element">
          <!-- Elements will create authentication element here -->
        </div>
        <div id="payment-element">
          <!-- Elements will create form elements here -->
        </div>
        <button id="submit">Pay now</button>
        <div id="error-message">
          <!-- Display error message to your customers here -->
        </div>
      </form>

      <div id="messages" role="alert" style="display: none;"></div>
    </main>
  </body>
</html>