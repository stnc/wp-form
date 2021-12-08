<?php
if (!defined('ABSPATH')) {
    exit;
}


//https://wordpress.org/support/topic/feature-request-get_current_user_id/

add_filter('jwt_auth_token_before_dispatch', 'stnc_jwt_auth_token_extra_fields', 10, 2);

/**
 * Adds a website parameter to the auth.
 *
 */
function stnc_jwt_auth_token_extra_fields($data, $user)
{
    //  $data['website'] = 'https://mysite.com';
    //   print_r($user);
    //   return
    //   print_r(    get_userdata($user->data->ID ));
    // //https://developer.wordpress.org/reference/functions/get_the_author_meta/
    //   print_r( get_the_author_meta( 'description',  $user->data->ID ));

    $data['user_id'] = $user->data->ID;
    $data['registered_date'] = $user->data->user_registered;
    $data['url'] = $user->data->user_url;
    $data['display_name'] = $user->data->display_name;
    $data['user_registered'] = $user->data->user_registered;
    $data['description'] = get_user_meta($user->data->ID, 'description', true);
    $data['avatar120'] = get_avatar_url($user->data->ID, 120); // $a= get_avatar( $user->data->ID, 120);
    $data['webSite'] = get_the_author_meta('user_url', $user->data->ID);
    $data['slug'] = $user->data->user_login;

    return $data;
}



/*******************************************************
 *************  accordiondata -start (REST API) *******
 *******************************************************/
/**
 *
 * Registers REST API endpoints -- for  accordion tab and categories with it depends posts
 *
 * @since 1.0.0
 */
add_action('rest_api_init', 'register_categoriesAndDepencyPostList');
function register_categoriesAndDepencyPostList()
{
    register_rest_route('wp/v2', '/categoriesAndDepencyPostList/',
        array(
            'methods' => 'GET',
            'callback' => 'categoriesAndDepencyPostList',
            'permission_callback' => function ($request) {
                return is_user_logged_in();
            },
        )
    );

}

/**
 * Gets categories and depency post list
 *
 * @since 6.2.0
 * @param WP_REST_Request $request The request sent from WP REST API.
 * @return array Gets quiz list
 */
function categoriesAndDepencyPostList(WP_REST_Request $request)
{

//   print_r($request->get_params());
    $categories = get_categories(array(
        'orderby' => 'id',
        'order' => 'ASC',
    ));

    // $uncategorized = 1;

    if (isset($request['uncategorized'])) {
        $uncategorized = $request['uncategorized'];
    } else {
        $uncategorized = 0;
    }

    $data = [];
    // $catPost= query_posts( array( 'category__and' => array(210), 'posts_per_page' => 2, 'orderby' => 'title', 'order' => 'DESC' ) );
    $i = 0;
    foreach ($categories as $key_ => $category) {

        if ($uncategorized == 0) {
            if ($category->name != "Uncategorized") {

                $posts_args = array(
                    'category__and' => array($category->term_id),
                    'post_status' => 'publish',
                    'orderby' => 'id',
                    'order' => 'ASC',
                    'no_found_rows' => true,
                );

                // The Featured Posts query.
                $posts = new WP_Query($posts_args);

                $posts = $posts->get_posts();

                foreach ($posts as $key2 => $post) {
                    $random = substr(md5(mt_rand()), 0, 5);
                    $data[$i]['catId'] = $category->term_id;
                    $data[$i]['catTitle'] = $category->name;
                    $data[$i]['key'] = 'st' . $random;
                    $data[$i]['posts'][$key2]['postId'] = $post->ID;
                    $data[$i]['posts'][$key2]['title'] = get_the_title($post->ID);
                }

                // Reset the post data
                wp_reset_postdata();
                $i++;

            }
        } else {
            $posts_args = array(
                'category__and' => array($category->term_id),
                'post_status' => 'publish',
                'orderby' => 'id',
                'order' => 'ASC',
                'no_found_rows' => true,
            );

            // The Featured Posts query.
            $posts = new WP_Query($posts_args);

            $posts = $posts->get_posts();

            foreach ($posts as $key2 => $post) {
                $random = substr(md5(mt_rand()), 0, 3);
                $data[$i]['catId'] = $category->term_id;
                $data[$i]['catTitle'] = $category->name;
                $data[$i]['key'] = 'st' . $random;
                $data[$i]['posts'][$key2]['postId'] = $post->ID;
                $data[$i]['posts'][$key2]['title'] = get_the_title($post->ID);
            }

            // Reset the post data
            wp_reset_postdata();
            $i++;
        }

    }

    return $data;
}

/*******************************************************
 **********  quizlcat result save -start (REST API) ***
 *******************************************************/

add_action('rest_api_init', 'stnc_quiz_result_register_rest_routes');

/**
 * Registers REST API endpoints
 *
 * @since 5.2.0
 */
function stnc_quiz_result_register_rest_routes()
{


    register_rest_route('wp/v2', '/quizcat/result/', array(
        'methods' => 'POST',
        'callback' => 'stnc_quizcat_save_question',
        'permission_callback' => function ($request) {
            return is_user_logged_in();
        },
    ));
}

function stnc_quizcat_save_question(WP_REST_Request $request)
{
// print_r($request);
    // return;
    global $wpdb;

    $tableName = $wpdb->prefix . 'stnc_cat_quiz';
    $request_type = "1"; //1 api , 2 web
    $status_ok = 'ok';
    $status_error = 'error';
    $error = [];

    if (isset($request['quiz_id']) && ($request['quiz_id'])) {
        $quiz_id = $request['quiz_id'];
    } else {
        $error[] = 'quiz_id not found';
    }

    if (isset($request['user_id']) && ($request['user_id'])) {
        $user_id = $request['user_id'];
    } else {
        $error[] = 'user_id not found';
    }

    if (isset($request['post_id']) && ($request['post_id'])) {
        $post_id = $request['post_id'];
    } else {
        $error[] = 'post_id not found';
    }

    $sql = "SELECT count(*) FROM  $tableName WHERE `quiz_id` = $quiz_id and `user_id` =  $user_id  and `post_id`=$post_id ";
    $rowcount = $wpdb->get_var($sql);

    if ($rowcount > 0) {
        $error[] = 'duplicate data';
        $status_error = 'duplicate';
    }

    $quiz_name ="";
    
    if (isset($request['quiz_name'])) {
        $quiz_name = $request['quiz_name'];
    }
    $point_score = 0;
    if (isset($request['point_score'])) {
        $point_score = $request['point_score'];
    }
    $correct =0;
    if (isset($request['correct'])) {
        $correct = $request['correct'];
    }
    $total =0;
    if (isset($request['total'])) {
        $total = $request['total'];
    }
    $user_ip ="";
    if (isset($request['user_ip'])) {
        $user_ip = $request['user_ip'];
    }
    $quiz_results = "";
    if (isset($request['quiz_results'])) {
        $quiz_results = $request['quiz_results'];
    }

    // print_r($error);
    if (!empty($error)) {
        return array(
            'status' => $status_error,
            'msg' => implode(',', $error),
        );
    } else {
        $arr = array(
            "quiz_id" => $quiz_id,
            "user_id" => $user_id,
            "post_id" => $post_id,
            "quiz_name" => $quiz_name,
            "point_score" => $point_score,
            "correct" => $correct,
            "total" => $total,
            "user_ip" => $user_ip,
            "quiz_results" => $quiz_results,
            "request_type" => $request_type,
        );

        $wpdb->insert($tableName, $arr);
        return array(
            'status' => $status_ok,
            'msg' => 'save successful',
        );
    }

}

/**********************************************************************
 *************  quiz cat id ye göre sonuçları verir -start (REST API) ***
 **********************************************************************/

/**
 * Registers REST API endpoints -- for  quiz ID
 *
 * @since 1.0.0
 */
add_action('rest_api_init', 'register_quizcat_id');
function register_quizcat_id()
{
    register_rest_route('wp/v2', '/quizcat/id/(?P<id>[\d]+)',
        array(
            'methods' => 'GET',
            'callback' => 'stnc_quiz_id',
            // 'permission_callback' => function ($request) {
            //     return is_user_logged_in();
            // },
        )
    );
}

/**
 * Gets quiz id request
 *
 * @since 6.2.0
 * @param WP_REST_Request $request The request sent from WP REST API.
 * @return array Gets quiz list
 */
function stnc_quiz_id(WP_REST_Request $request)
{
    extract($request->get_params());
    return [stnc_quiz_func($id)];
}

/**
 * Gets quiz id request
 *
 * @since 6.2.0
 * @param int $post_id The request sent post_id
 * @return array Gets quiz list
 */
function stnc_quiz_func($post_id)
{

    // $post = get_post( 52 );
    // print_r($post);

    $args = array('post_type' => 'fca_qc_quiz', 'p' => $post_id);
    $the_query = new WP_Query($args);
    if ($the_query->have_posts()) {

        $all_meta = get_post_meta($post_id, '', true);
        $quiz_meta = empty($all_meta['quiz_cat_meta']) ? array() : unserialize($all_meta['quiz_cat_meta'][0]);
        $quiz_meta['title'] = get_the_title($post_id);
        $questions = empty($all_meta['quiz_cat_questions']) ? array() : unserialize($all_meta['quiz_cat_questions'][0]);
        $quiz_results = empty($all_meta['quiz_cat_results']) ? array() : unserialize($all_meta['quiz_cat_results'][0]);
        $quiz_settings = empty($all_meta['quiz_cat_settings']) ? array() : unserialize($all_meta['quiz_cat_settings'][0]);
        $data = [
            'title' => $quiz_meta['title'],
            'questions' => $questions,
            'quiz_results' => $quiz_results,
            'quiz_settings' => $quiz_settings,
        ];
        return stnc_quiz_func_manipulationV4($data);
    } else {
        return   

        array(
            'status' => 'not_url_find',
            'msg' => 'not_url_find',
        );

    }

}

// echo '<pre>';
// print_r(stnc_quiz_func(1834));
// print_r(donder(stnc_quiz_func(1834)));

/**
 * Gets quiz list array manipulation
 *
 * @since 6.2.0
 * @param array $datas quiz list array
 * @return array Gets quiz list
 */
function stnc_quiz_func_manipulationV4($datas)
{
    foreach ($datas['questions'] as $key => $data) {
        foreach ($data['answers'] as $keyAnswer => $answer) {
            $datas['questions'][$key]['answers']['answers' . $keyAnswer] = $answer['answer'];
            $datas['questions'][$key]['correctOption'] = 'answers0';
            unset($datas['questions'][$key]['answers'][$keyAnswer]);
        }
        $datas['questions'][$key]['answers'] = shuffle_assoc($datas['questions'][$key]['answers']);
    }
    return $datas;
}

/**********************************************************************
 *************  quiz cat id ye göre sonuçları verir -start (REST API) ***
 **********************************************************************/

/**
 * Registers REST API endpoints -- for  quiz ID  quiz cat id ye göre sonuçları verir
 *
 * @since 1.0.0
 */
add_action('rest_api_init', 'register_ULike_rest');
function register_ULike_rest()
{
    register_rest_route('wp/v2', '/stnc_like/', array(
        // 'methods' => WP_REST_Server::CREATABLE,
        'methods' => 'POST',
        'callback' => 'register_uLike_rest_wp_insert',
        'permission_callback' => function ($request) {
            return is_user_logged_in();
        },
    ));
}

/**
 * Gets quiz id request   -- for  quiz ID  quiz cat id ye göre sonuçları verir
 *
 * @since 6.2.0
 * @param WP_REST_Request $request The request sent from WP REST API.
 * @return array Gets quiz list
 */
function register_uLike_rest_wp_insert(WP_REST_Request $request)
{

    global $wpdb, $wp_user_IP;

    define('DEBUG', false);
    error_reporting(E_ALL);

    if (DEBUG) {
        ini_set('display_errors', 'On');
    } else {
        ini_set('display_errors', 'Off');
    }

    // $user_id = 11;
    // $post_id = 56;

    if (isset($request['user_id'])) {
        $user_id = $request['user_id'];
    }

    if (isset($request['post_id'])) {
        $post_id = $request['post_id'];
    }

    // $time= date("Y-m-d H:i:s");
    $time = current_time('mysql', 1);

    //user daha once
    $user_status = get_user_status($post_id, $user_id);

    if (!$user_status) {
        $wpdb->insert(
            $wpdb->prefix . 'ulike',

            array(
                'user_id' => $user_id,
                'date_time' => $time,
                'ip' => $wp_user_IP,
                'post_id' => $post_id,
                'status' => 'like',
            ),

            array('%d', '%s', '%s', '%s', '%s')
        );
        $status = "like";
    } else {
        // $this->update_status($factor, $user_status);
        // Update status
        if ($user_status) {
            if ($user_status == "like") {
                $status = "unlike";
            } else {
                $status = "like";
            }
        }

        $wpdb->update($wpdb->prefix . 'ulike',
            array('status' => $status),
            array('user_id' => $user_id, 'post_id' => $post_id));

    }
    $total = get_total_like_for_like($post_id);
    switch ($status) {
        case "like":
            return $response = array(
                'message' => __('Thanks! You Liked This.', 'selman'),
                // 'messageDB' =>  $status,
                'messageType' => 'success',
                'status' => 'like',
                'total' => $total,
            );
            break;
        case "unlike":
            return $response = array(
                'message' => __('Sorry! You unliked this.', 'selman'),
                // 'messageDB' =>  $status,
                'messageType' => 'error',
                'status' => 'unlike',
                'total' => $total,
            );
    }

}

/**
 * Get User Status (like/dislike)  -- for  quiz ID  quiz cat id ye göre sonuçları verir
 * @param           int $post_id
 * @param           int $user_id
 * @since           2.0
 * @return            String
 */
function get_user_status($post_id, $user_id)
{
    global $wpdb;

    $query = "SELECT `status`	FROM " . esc_sql($wpdb->prefix . 'ulike') . " WHERE `post_id` = '" . esc_sql($post_id) . "' AND `user_id` = '" . esc_sql($user_id) . "'  ";

    $result = $wpdb->get_var($query);

    return empty($result) ? false : $result;
}

/**************************************************************************************************
 *************  single ve all post içindeki dataya toplam like sayısını ekler -start (REST API) ***
 **************************************************************************************************/
add_action('rest_api_init', 'stnc_total_ulike');

function stnc_total_ulike()
{
    register_rest_field(
        'post',
        'totalLike',
        array(
            'get_callback' => 'stnc_get_total_ulike',
            'schema' => null)
    );
}

/**
 * Handler for getting custom field data.
 */
function stnc_get_total_ulike($object, $field_name, $request)
{
    //header datasını okur
    // foreach (getallheaders() as $name => $value) {
    //     echo "$name: $value <br>";
    // }

    return get_total_like_for_like($object['id']);
}

/**************************************************************************************************
 *************  single ve all post içindeki dataya user id isteğine user ın like sayısını ekler -start (REST API) ***
 **************************************************************************************************/
add_action('rest_api_init', 'stnc_total_ulike_userID');

function stnc_total_ulike_userID()
{
    register_rest_field(
        'post',
        'meLike',
        array(
            'get_callback' => 'stnc_get_total_ulike_userID',
            'schema' => null)
    );
}

/**
 * Handler for getting custom field data.
 */
function stnc_get_total_ulike_userID($object, $field_name, $request)
{

    if (isset($request['userID']) && ($request['userID'])) {
        $userID = $request['userID'];
        return get_total_like_for_like_userME($object['id'], $userID);
    } else {
        return 'unlike';
    }

}





    /**
     * Register the routes for the objects of the controller.
     */
   function register_endpoints() {
 

        register_rest_route('wp/v2', '/logins/', array(
            // 'methods' => WP_REST_Server::CREATABLE,
            'methods' => 'GET',
            'callback' => 'login',
            'permission_callback' => function ($request) {
                return is_user_logged_in();
            },
        ));
    }

    /**
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Request
     */
function login(WP_REST_Request $request) {


        $data = array();
        $data['user_login'] = $request["username"];
        $data['user_password'] =  $request["password"];
        $data['remember'] = true;
        $user = wp_signon( $data, false );
print_r($user);
        if ( !is_wp_error($user) )
          return $user;
    }


//add_action('rest_api_init', 'register_endpoints');