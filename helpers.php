<?php

function basePath($path = '')
{
    return __DIR__ . '/' . $path;
}



function loadView($name)
{
    $viewPath = basePath("views/pages/{$name}.php");
    if (file_exists($viewPath)) {
        require $viewPath;
    } else {
        echo "View '{$name}' not found.";
    }
}


function loadPartial($name)
{
    $partialPath = basePath("{$name}.php");
    if (file_exists($partialPath)) {
        require $partialPath;
    } else {
        echo "Partial '{$name}' not found.";
    }
}


function inspect($value)
{
    echo "<pre>";
    var_dump($value);
    echo "</pre>";
}


function inspectAndDie($value)
{
    echo "<pre>";
    var_dump($value);
    echo "</pre>";
    die();
}

function getTimeElapsedString($datetime, $full = false)
{
    date_default_timezone_set('Asia/Dhaka');

    $now = new DateTime;
    $now->setTimezone(new DateTimeZone('Asia/Dhaka'));
    $ago = new DateTime($datetime);
    $ago->setTimezone(new DateTimeZone('Asia/Dhaka'));
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = [
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    ];

    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'Just now';
}


session_start();
function is_user_logged_in()
{
    return isset($_SESSION['user']);
}


function admin_authentication()
{
    if (!(isset($_SESSION['user']) && $_SESSION['user']['email'] == 'admin@email.com')) {
        echo "<script>window.location.href = '404.php';</script>";
    }
}

function success_message($text)
{
    return "<div id='alert' class='flex items-center p-4 mb-4 text-green-800 rounded-lg bg-green-50' role='alert'>
                <svg class='flex-shrink-0 w-4 h-4' aria-hidden='true' xmlns='http://www.w3.org/2000/svg' fill='currentColor'
                     viewBox='0 0 20 20'>
                    <path d='M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z'/>
                </svg>
                <span class='sr-only'>Info</span>
                <div class='ms-3 text-sm font-medium'>
                    $text
                </div>
                <button type='button'
                        class='ms-auto -mx-1.5 -my-1.5 bg-green-50 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-200 inline-flex items-center justify-center h-8 w-8 '
                       aria-label='Close' id='closeAlertBtn'>
                    <span class='sr-only'>Close</span>
                    <svg class='w-3 h-3' aria-hidden='true' xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 14 14'>
                        <path stroke='currentColor' stroke-linecap='round' stroke-linejoin='round' stroke-width='2'
                              d='m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6'/>
                    </svg>
                </button>
            </div>";
}

function warning_message($text)
{
    return "<div id='alert' class='flex items-center p-4 mb-4 text-yellow-800 rounded-lg bg-yellow-50' role='alert'>
                <svg class='flex-shrink-0 w-4 h-4' aria-hidden='true' xmlns='http://www.w3.org/2000/svg' fill='currentColor'
                     viewBox='0 0 20 20'>
                    <path d='M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z'/>
                </svg>
                <span class='sr-only'>Info</span>
                <div class='ms-3 text-sm font-medium'>
                    $text
                </div>
                <button type='button'
                        class='ms-auto -mx-1.5 -my-1.5 bg-yellow-50 text-yellow-500 rounded-lg focus:ring-2 focus:ring-yellow-400 p-1.5 hover:bg-yellow-200 inline-flex items-center justify-center h-8 w-8 '
                       aria-label='Close' id='closeAlertBtn'>
                    <span class='sr-only'>Close</span>
                    <svg class='w-3 h-3' aria-hidden='true' xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 14 14'>
                        <path stroke='currentColor' stroke-linecap='round' stroke-linejoin='round' stroke-width='2'
                              d='m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6'/>
                    </svg>
                </button>
            </div>";
}

function danger_message($text)
{
    return "<div id='alert' class='flex items-center p-4 mb-4 text-red-800 rounded-lg bg-red-50' role='alert'>
                <svg class='flex-shrink-0 w-4 h-4' aria-hidden='true' xmlns='http://www.w3.org/2000/svg' fill='currentColor'
                     viewBox='0 0 20 20'>
                    <path d='M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z'/>
                </svg>
                <span class='sr-only'>Info</span>
                <div class='ms-3 text-sm font-medium'>
                    $text
                </div>
                <button type='button'
                        class='ms-auto -mx-1.5 -my-1.5 bg-red-50 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex items-center justify-center h-8 w-8 '
                        aria-label='Close' id='closeAlertBtn'>
                    <span class='sr-only'>Close</span>
                    <svg class='w-3 h-3' aria-hidden='true' xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 14 14'>
                        <path stroke='currentColor' stroke-linecap='round' stroke-linejoin='round' stroke-width='2'
                              d='m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6'/>
                    </svg>
                </button>
            </div>";
}


function admin_success_message($text)
{
    return "<div id='alert' class='flex items-center p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400' role='alert'>
  <svg class='flex-shrink-0 inline w-4 h-4 me-3' aria-hidden='true' xmlns='http://www.w3.org/2000/svg' fill='currentColor' viewBox='0 0 20 20'>
    <path d='M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z'/>
  </svg>
  <span class='sr-only'>Info</span>
  <div>
    $text
  </div>
    <button type='button' id='closeAlertBtn' class='ms-auto -mx-1.5 -my-1.5 bg-green-50 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-200 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-green-400 dark:hover:bg-gray-700' data-dismiss-target='#alert' aria-label='Close'>
<span class='sr-only'>Close</span>
    <svg class='w-3 h-3' aria-hidden='true' xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 14 14'>
      <path stroke='currentColor' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6'/>
    </svg>
  </button>
</div>";
}
function admin_danger_message($text)
{
    return "<div id='alert' class='flex items-center p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400' role='alert'>
  <svg class='flex-shrink-0 inline w-4 h-4 me-3' aria-hidden='true' xmlns='http://www.w3.org/2000/svg' fill='currentColor' viewBox='0 0 20 20'>
    <path d='M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z'/>
  </svg>
  <span class='sr-only'>Info</span>
  <div>
    $text
  </div>
    <button type='button' id='closeAlertBtn' class='ms-auto -mx-1.5 -my-1.5 bg-red-50 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-red-400 dark:hover:bg-gray-700' data-dismiss-target='#alert' aria-label='Close'>
<span class='sr-only'>Close</span>
    <svg class='w-3 h-3' aria-hidden='true' xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 14 14'>
      <path stroke='currentColor' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6'/>
    </svg>
  </button>
</div>";
}
function admin_warning_message($text)
{
    return "<div id='alert' class='flex items-center p-4 mb-4 text-sm text-yellow-800 rounded-lg bg-yellow-50 dark:bg-yellow-800 dark:text-yellow-400' role='alert'>
  <svg class='flex-shrink-0 inline w-4 h-4 me-3' aria-hidden='true' xmlns='http://www.w3.org/2000/svg' fill='currentColor' viewBox='0 0 20 20'>
    <path d='M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z'/>
  </svg>
  <span class='sr-only'>Info</span>
  <div>
    $text
  </div>
    <button type='button' id='closeAlertBtn' class='ms-auto -mx-1.5 -my-1.5 bg-yellow-50 text-yellow-500 rounded-lg focus:ring-2 focus:ring-yellow-400 p-1.5 hover:bg-yellow-200 inline-flex items-center justify-center h-8 w-8 dark:bg-yellow-800 dark:text-yellow-400 dark:hover:bg-yellow-700' data-dismiss-target='#alert' aria-label='Close'>
<span class='sr-only'>Close</span>
    <svg class='w-3 h-3' aria-hidden='true' xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 14 14'>
      <path stroke='currentColor' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6'/>
    </svg>
  </button>
</div>";
}