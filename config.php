<?php
require 'stripe-php/init.php';

const STRIPE_SECRET_KEY = 'sk_test_51OxYrSRogH7j6YJA6HUWGRaqkSbAvFLmOLg95WHfWJIjfyFh1HcpDKyhqvIk1HdQA1sqbyMbLvZAjEY5QxW4NlPa00QZPAd1hK';
const STRIPE_PUBLISH_KEY = 'pk_test_51OxYrSRogH7j6YJAieFOUBb06lPb4RnhiUMHXYZLJQUu8dPwLc8j7Psnh8ydUjLp26fTgebYNK3qpUCfhvUEIji900jJTloNBE';

\Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);

