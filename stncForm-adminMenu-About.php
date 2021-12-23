<?php
function stncForm_adminMenu_About_contents()
{
?>
    <div id="advanced" class="postbox ">
        <div class="inside">
            <div class="card shadow1" style="max-width:100%!important">
                <h2>Erciyes Teknopark Video Yükleyici</h2>
                <p>Bu form erciyes teknopark video yükleme işlemi için Selman Tunç tarafından yapılmıştır</p>
                <pre>[StncForm_videoYukle]</pre>
                <p><mark class="dont">Ekleme:</mark>&nbsp; üstteki kodu editor içine ekleyiniz</p>
            </div>
        </div>
    </div>

<?php

}


function stncForm_adminMenu_About_contents2()
{
    set_current_screen();
    $admin_body_class = array( 'pll-wizard', 'wp-core-ui' );
    if ( is_rtl() ) {
        $admin_body_class[] = 'rtl';
    }
    ?>
    <!DOCTYPE html>
    <html <?php language_attributes(); ?>>
        <head>
            <meta name="viewport" content="width=device-width" />
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>
    
            ?>
            </title>
            <script type="text/javascript">
                var ajaxurl = '<?php echo esc_url( admin_url( 'admin-ajax.php', 'relative' ) ); ?>';
            </script>
     
        </head>
        <body class="<?php echo join( ' ', array_map( 'sanitize_key', $admin_body_class ) ); ?>">
    <div id="advanced" class="postbox ">
        <div class="inside">
            <div class="card shadow1" style="max-width:100%!important">
                <h2>sssss Erciyes Teknopark Video Yükleyici</h2>
                <p>Bu form erciyes teknopark video yükleme işlemi için Selman Tunç tarafından yapılmıştır</p>
                <pre>[StncForm_videoYukle]</pre>
                <p><mark class="dont">Ekleme:</mark>&nbsp; üstteki kodu editor içine ekleyiniz</p>
            </div>
        </div>
    </div>
	</body>
</html>
<?php

}

///kullanılmıyor 
function   stncForm_adminMenu_About_test()
{

?>
    <div class="col-container">
        <div class="col-left">
            <div class="col-wrap">

                <div class="card shadow1">
                    <h2>Erciyes Teknopark Video Yükleyici</h2>
                    <p>Bu form erciyes teknopark video yükleme işlemi için Selman Tunç tarafından yapılmıştır</p>
                    <pre>[StncForm_RegisterForm]</pre>
                    <p><mark class="dont">Ekleme:</mark>&nbsp; üstteki kodu editor içine ekleyiniz</p>
                </div>

                <div class="card shadow1">
                    <h2>Erciyes Teknopark Video Yükleyici</h2>
                    <p>Bu form erciyes teknopark video yükleme işlemi için Selman Tunç tarafından yapılmıştır</p>
                    <pre>[StncForm_RegisterForm]</pre>
                    <p><mark class="dont">Ekleme:</mark>&nbsp; üstteki kodu editor içine ekleyiniz</p>
                </div>

            </div>
        </div>

        <div class="col-right">
            <div class="col-wrap">


            </div>
        </div>
    </div>








<?php

}
