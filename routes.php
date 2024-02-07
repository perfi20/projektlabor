<?php

require_once __DIR__.'/router.php';

// home page
get('/', '/index.php'); 
get('/post/$postByID', '/index.php');
get('/posts/from/$postsByUser/page/$page', '/index.php');
get('/posts/from/$postsByUser', '/index.php');
get('/posts/category/$postsByCategory/page/$page', '/index.php');
get('/posts/category/$postsByCategory', '/index.php');
get('/posts/date/$postsByYear/$postsByMonth/page/$page', '/index.php');
get('/posts/date/$postsByYear/$postsByMonth', '/index.php');

// login
get('/login', '/login.php');
post('/login', '/login.php');

// register
get('/register', '/register.php');
post('/register', '/register.php');

// logout
get('/logout', '/inc/logout.php');

// admin
//get('/admin', '/admin.php');
any('/admin/$view', '/admin.php');

//post('/admin/view/$view', '/admin.php');

// post manager
get('/user/posts', '/post_manager.php');
post('/user', '/post_manager.php');
get('/user/posts/$view', '/post_manager.php');
post('/user/posts', '/post_manager.php');

// user manager
get('/user/profile', '/user_manager.php');
post('/user/profile', '/user_manager.php');

// resources
// get('/js/eventHandler', '/js/eventHandler.js');
// get('/js/postManager', '/js/postManager.js');
//get('/admin/src/$folder/$resource', '/inc/loggedInHeader.php');