<?php

namespace YC\Admin\CPT\Game;


if (!class_exists('yc_AdminPageFramework_MetaBox')) return;

class _Game_Setting extends \yc_AdminPageFramework_MetaBox
{
    public function start()
    {
        add_action('the_content', array($this, 'replyToAddContent'));
        add_action('post_submitbox_misc_actions', [$this, 'add_in_submitbox'], 100);
    }

    public function add_in_submitbox()
    {

        if(get_current_screen()->id == 'gg_game'):
        global $post;
        $html = '';
        ob_start();
?>
        <div id="major-publishing-actions">
            <select class="text-center" size="1" id="result__0" name="result" data-id="result__0">
                <option id="result__0_0" value="0" selected="selected">請選擇賽事結果</option>
                <option id="result__0_1" value="1">A隊獲勝</option>
                <option id="result__0_2" value="2">B隊獲勝</option>
                <option id="result__0_3" value="3">平手</option>
                <option id="result__0_4" value="4">賽事取消</option>
            </select>
        </div>
    <?php
        $html .= ob_get_clean();
        echo $html;

        endif;
    }

    /**
     * Called when a post content is displayed.
     *
     * @callback    filter      the_content
     * @return      string
     */
    public function replyToAddContent($sContent)
    {

        if (!is_singular()) {
            return $sContent;
        }
        if (!is_main_query()) {
            return $sContent;
        }
        global $post;
        if ('project' !== $post->post_type) {
            return $sContent;
        }

        //$slide_imgs = get_post_meta($post->ID, 'meta_key', true);
        $html = '';


        ob_start();
    ?>
        test
    <?php
        $html .= ob_get_clean();

        return $html . $sContent;
    }

    /*
     * Use the setUp() method to define settings of this meta box.
     */
    public function setUp()
    {

        $this->addSettingSections(
            array(
                'section_id'        => 'game_date_sec',
                'section_tab_slug'  => 'tabbed_sections',   // <-- set a unique tab slug
                'title'             => '賽事時間',
            ),
            array(
                'section_id'        => 'game_bet_sec',
                'section_tab_slug'  => 'tabbed_sections',   // <-- set a unique tab slug
                'title'             => '下注狀況',
            ),
            array(
                'section_id'        => 'game_result_sec',
                'section_tab_slug'  => 'tabbed_sections',   // <-- set a unique tab slug
                'title'             => '賽事狀態',
            ),
        );

        /**
         * Adds setting fields in the meta box.
         */
        $this->addSettingFields(
            'game_date_sec',
            array(
                'field_id'      => 'date_group',
                'type'          => 'inline_mixed',
                'content'    => array(
                    array(
                        'field_id'        => 'start',
                        'type'            => 'date',
                        'title'           => '開始日期',
                    ),
                    array(
                        'field_id'        => 'end',
                        'type'            => 'date',
                        'title'           => '結束日期',
                    ),
                ),
            ),
            array(
                'field_id'      => 'teamA_group',
                'type'          => 'inline_mixed',
                'content'    => array(
                    array(
                        'field_id'        => 'teamA_name',
                        'type'            => 'text',
                        'title'           => 'A 隊伍名稱',
                    ),
                    array(
                        'field_id'        => 'teamA_img',
                        'type'            => 'image',
                        'title'           => 'A 隊伍圖片',
                    ),
                ),
            ),
            array(
                'field_id'      => 'teamB_group',
                'type'          => 'inline_mixed',
                'content'    => array(
                    array(
                        'field_id'        => 'teamB_name',
                        'type'            => 'text',
                        'title'           => 'B 隊伍名稱',
                    ),
                    array(
                        'field_id'        => 'teamB_img',
                        'type'            => 'image',
                        'title'           => 'B 隊伍圖片',
                    ),
                ),
            ),
        );


        //START DEV
        $html = '';
        ob_start();
    ?>
        <table class="table-border w-100">
            <thead>
                <tr>
                    <th>#</th>
                    <th class="bg-light-red">A 隊</th>
                    <th class="bg-light-blue">B 隊</th>
                    <th>差異</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th>賠率</th>
                    <td class="bg-light-red">0.6</td>
                    <td class="bg-light-blue">1.5</td>
                    <td>-60%</td>
                </tr>
                <tr>
                    <th>總注金</th>
                    <td class="bg-light-red">1,500,000</td>
                    <td class="bg-light-blue">1,000,000</td>
                    <td>+50%</td>
                </tr>

                <tr>
                    <th>注數</th>
                    <td class="bg-light-red">3,106</td>
                    <td class="bg-light-blue">2,401</td>
                    <td>+29%</td>
                </tr>
                <tr>
                    <th>下單用戶數</th>
                    <td class="bg-light-red">120</td>
                    <td class="bg-light-blue">84</td>
                    <td>+43%</td>
                </tr>
            </tbody>
        </table>

    <?php
        $html .= ob_get_clean();

        //END DEV
        $this->addSettingFields(
            'game_bet_sec',
            array(
                'field_id'      => 'project_subtitle',
                //'type'          => 'text',
                'content'    => $html,
            ),
        );

        $this->addSettingFields(
            'game_result_sec',
            array(
                'field_id'      => 'result',
                'type'          => 'select',
                'help'          => __('This is the <em>select</em> field type.', 'yc_tech'),
                'default'       => 0,
                'label'         => array(
                    0 => __('請選擇勝利方', 'yc_tech'),
                    1 => __('A 隊獲勝', 'yc_tech'),
                    2 => __('B 隊獲勝', 'yc_tech'),
                    3 => __('平手', 'yc_tech'),
                    4 => __('賽事取消', 'yc_tech'),
                ),
            ),
            array(
                'field_id'      => 'teamA_group',
                'type'          => 'inline_mixed',
                'content'    => array(

                    array(
                        'field_id'        => 'teamA_score',
                        'type'            => 'text',
                        'tip'             => '也有可能只有輸贏，沒有比分',
                        'title'           => 'A 隊得分',
                    ),
                    array(
                        'field_id'        => 'teamB_score',
                        'type'            => 'text',
                        'tip'             => '也有可能只有輸贏，沒有比分',
                        'title'           => 'B 隊得分',
                    ),
                ),
            ),
        );
    }
}

new _Game_Setting(
    'gg_game_setting',   // meta box ID - can be null.
    __('賽事設定', 'yc_tech'), // title
    array('gg_game'),                               // post type slugs: post, page, etc.
    'normal',                                        // context
    'high'                                          // priority
);


//-----------------------------------------------------------------//


class _Game_Result extends \yc_AdminPageFramework_MetaBox
{
    public function start()
    {

        add_action('the_content', array($this, 'replyToAddContent'));
    }
    /**
     * Called when a post content is displayed.
     *
     * @callback    filter      the_content
     * @return      string
     */
    public function replyToAddContent($sContent)
    {

        if (!is_singular()) {
            return $sContent;
        }
        if (!is_main_query()) {
            return $sContent;
        }
        global $post;
        if ('project' !== $post->post_type) {
            return $sContent;
        }

        //$slide_imgs = get_post_meta($post->ID, 'meta_key', true);
        $html = '';


        ob_start();
    ?>
        test
<?php
        $html .= ob_get_clean();

        return $html . $sContent;
    }

    /*
     * Use the setUp() method to define settings of this meta box.
     */
    public function setUp()
    {


        /**
         * Adds setting fields in the meta box.
         */

        $this->addSettingFields(
            array(
                'field_id'      => 'result',
                'title'         => __('賽事結果', 'yc_tech'),
                'type'          => 'select',
                'help'          => __('This is the <em>select</em> field type.', 'yc_tech'),
                'default'       => 0,
                'label'         => array(
                    0 => __('請選擇', 'yc_tech'),
                    1 => __('A隊獲勝', 'yc_tech'),
                    2 => __('B隊獲勝', 'yc_tech'),
                    3 => __('平手', 'yc_tech'),
                    4 => __('賽事取消', 'yc_tech'),
                ),
            ),
        );
    }
}
/*
new _Game_Result(
    null,   // meta box ID - can be null.
    __('賽事狀態', 'yc_tech'), // title
    array('gg_game'),                               // post type slugs: post, page, etc.
    'side',                                        // context
    'high'                                          // priority
);
*/
