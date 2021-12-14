<?php

/**
 * customise admin
 */

namespace YC\Admin\Integrate;

use YC_TECH;

defined('ABSPATH') || exit;

if (!class_exists('Bogo_POMO', false)) return;

class _bogo extends YC_TECH
{

    public function __construct()
    {
        add_filter('bogo_localizable_post_types', [$this, 'yc_bogo_support_for_custom_post_types'], 10, 1);
        add_action('pre_get_posts', [$this, 'change_query_var_by_locale']);
    }

    public function logic_for_edit_multilang()
    {
        global $pagenow;
        # Check current admin page.
        if ($pagenow == 'edit.php' && !isset($_GET['lang']) ) {
            return true;
        }else{
            return false;
        }

    }

    public function change_query_var_by_locale($query)
    {

        global $pagenow;
        # Check current admin page.
        if($pagenow == 'edit.php'){
            add_action( 'admin_footer', [$this, 'change_lang_select'] );
        }

        if ($this->logic_for_edit_multilang()) {
            $locale = get_user_meta(get_current_user_id(), 'locale', true);
            $query->set('meta_query', array(
                array(
                    'key'     => '_locale',
                    'value'   => $locale,
                )
            ));
        }
        return $query;
    }

    public function change_lang_select(){
        $locale = get_user_meta(get_current_user_id(), 'locale', true);

?>
<script>
    jQuery(document).ready(function(){
        <?php if($this->logic_for_edit_multilang()): ?>
        jQuery('select[name="lang"]').val('<?php echo $locale; ?>');
        jQuery('h1.wp-heading-inline').append(' - 只有抓出語言 <?php echo $locale; ?> 的資料');
<?php elseif(isset($_GET['lang'])): ?>

    <?php if(empty($_GET['lang'])): ?>
        jQuery('h1.wp-heading-inline').append(' - 抓出全部語言的資料');
    <?php else: ?>
    jQuery('h1.wp-heading-inline').append(' - 只有抓出語言 <?php echo $_GET['lang']; ?> 的資料');

    <?php endif; ?>

        <?php endif; ?>


    });

</script>
<?
    }

    /**
     * Support custom post type with bogo.
     * @param array $ locallyizable Supported post types.
     */
    public function yc_bogo_support_for_custom_post_types($localizable)
    {
            $args = array(
                'public' => true,
                '_builtin' => false,
            );
            $custom_post_types = get_post_types($args); //抓出所有的post type

            return array_merge($localizable, $custom_post_types);
    }
}

new _bogo();