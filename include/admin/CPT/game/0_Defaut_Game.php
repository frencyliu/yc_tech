<?php

/**
 * Game post type
 * 賽事系統
 * 前端、短碼都在這
 */

namespace YC\Admin\CPT\Game;

use YC_TECH;

defined('ABSPATH') || exit;

if (!BET) return;


class _Default extends YC_TECH
{
    public function __construct()
    {
        add_action('init', [$this, 'add_shortcode']);
    }



    public function add_shortcode()
    {
        add_shortcode('game_list', [$this, 'game_list_f']);
    }

    public function game_list_f($atts = array())
    {

        // set up default parameters
        extract(shortcode_atts(array(
            'loop_class' => 'col-12',
            'ratio'      => '16/9',
        ), $atts));


        $args = array(
            'posts_per_page' => 8,
            'orderby'        => 'date',
            'order'          => 'DESC',
            'post_type'      => 'gg_game',
            //'genre'          => 'jazz',
            'post_status'    => 'publish',
            /*'tax_query' => array(
        array(
            'taxonomy' => 'genre',
            'field'    => 'slug',
            'terms'    => 'jazz'
        )
    ),
    'meta_query' => array(
        'relation' => 'AND',
        'state_clause' => array(
            'key' => 'state',
            'value' => 'Wisconsin',
        ),
        'city_clause' => array(
            'key' => 'city',
            'compare' => 'EXISTS',
        ),
    ),*/
        );

        $games = get_posts($args);

        $html = '';
        ob_start();

?>
        <style>
            .gg_game .gamipress-points-thumbnail {
                width: 24px;
                height: 24px;
            }

            #gg_game #alert {
                display: none;
            }
            .game-loop{
                margin-bottom: 1rem;
            }
        </style>

        <div class="row">
            <?php foreach ($games as $game) :
                $game_info = get_post_meta($game->ID, 'game_date_sec');
                $game_result = get_post_meta($game->ID, 'game_result_sec');

                switch ($game_result[0]['result']) {
                    case '0':
                        $status = 'bytime';
                        break;
                    case '1':
                        $status = $game_info[0]['teamA_group']['teamA_name'] . '獲勝';
                        break;
                    case '2':
                        $status = $game_info[0]['teamB_group']['teamB_name'] . '獲勝';
                        break;
                    case '3':
                        $status = '平手';
                        break;
                    case '4':
                        $status = '賽事取消';
                        break;

                    default:
                        $status = 'bytime';
                        break;
                }

                if ($status == 'bytime') {
                    $game_start = strtotime($game_info[0]['date_group']['start']);
                    $game_end = strtotime($game_info[0]['date_group']['end']);
                    if (time() < $game_start) {
                        //比賽還沒開始
                        $status = '可下注';
                    } elseif (time() < $game_end) {
                        //比賽中
                        $status = '比賽進行中';
                    } else {
                        $status = '比賽已結束';
                    }
                }
            ?>
                <div class="<?php echo $loop_class; ?> game-loop">
                    <div class="lc-block card card-cover pt-5 rounded-0 h-50 overflow-hidden text-white bg-dark shadow-lg w-100" lc-helper="background" style="<?php echo 'aspect-ratio:' . $ratio; ?>;background:url(<?php echo get_the_post_thumbnail_url($game->ID, 'large'); ?>)  center / cover no-repeat;">
                        <div class="d-flex flex-column h-100 pb-3 text-white text-shadow-1 justify-content-end">
                            <div class="lc-block">
                                <div editable="rich">
                                    <h2 class="h5 lh-1 fw-light text-center"><?php echo $game->post_title; ?></h2>
                                    <p class="text-center">比賽時間：<?php echo $game_info[0]['date_group']['start']; ?> - <?php echo $game_info[0]['date_group']['end']; ?> <i class="fas fa-info-circle"></i></p>
                                    <span class="position-absolute top-0 end-0 badge bg-light text-primary"><?php echo $status ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="row mx-0">
                            <div class="col-6 px-0">
                                <button class="game_team w-100 border-0 py-2 px-3 bg-warning d-flex justify-content-between border-end border-3 border-secondary" data-bs-toggle="modal" data-bs-target="#gg_game" game-id="<?php echo $game->ID; ?>" team-name="<?php echo $game_info[0]['teamA_group']['teamA_name']; ?>">
                                    <span class="team_name"><?php echo $game_info[0]['teamA_group']['teamA_name']; ?></span>
                                    <span>賠率<span class="bet_ratio">1.5</span></span>
                                </button>


                            </div>
                            <div class="col-6 px-0">
                                <button class="game_team w-100 border-0 py-2 px-3 bg-warning d-flex justify-content-between" data-bs-toggle="modal" data-bs-target="#gg_game" game-id="<?php echo $game->ID; ?>" team-name="<?php echo $game_info[0]['teamB_group']['teamB_name']; ?>">
                                    <span class="team_name"><?php echo $game_info[0]['teamB_group']['teamB_name']; ?></span>
                                    <span>賠率<span class="bet_ratio">11.7</span></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            <?php endforeach; ?>

        </div>
        <!-- Modal -->
        <div class="modal fade gg_game" id="gg_game" tabindex="-1" aria-labelledby="gg_gameLabel" aria-hidden="true">
            <div class="modal-dialog d-flex align-items-center h-100">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="gg_gameLabel">選擇下注金額</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="game_team">
                            <!--button class="w-100 border-0 py-2 px-3 bg-warning d-flex justify-content-between mb-3" data-bs-toggle="modal" data-bs-target="#gg_game">
                            <span>費城76人</span>
                            <span>賠率<span class="bet_ratio">1.5</span></span>
                        </button-->
                        </div>
                        <form action="">
                            <div class="input-group mb-3">
                                <span class="input-group-text">NT$</span>
                                <input id="bet_amount" type="text" class="form-control text-end" aria-label="Amount (to the nearest dollar)">
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <p class="small text-start">
                                    剩餘<?php echo do_shortcode('[gamipress_points inline=yes]'); ?>
                                </p>
                                <p class="small text-end">預期獲利：<span id="expect_earn">NT$ 10,000</span></p>
                            </div>
                        </form>
                        <div id="alert">
                            <div class="alert alert-danger d-flex align-items-center" role="alert">
                                <i class="fas fa-exclamation-triangle me-3"></i>
                                <div id="confirm_text">
                                    您即將下注
                                </div>

                            </div>
                            <button id="sure_confirm" class="w-100 btn btn-danger">確定繼續</button>
                        </div>
                    </div>
                    <div class="modal-footer flex-nowrap">
                        <button type="button" class="w-50 btn btn-secondary" data-bs-dismiss="modal">取消</button>
                        <button id="place_bet" type="button" class="w-50 btn btn-primary">下注</button>
                    </div>
                </div>
            </div>
        </div>
<?php
        $html .= ob_get_clean();
        return $html;
    }
}
new _Default();



require_once __DIR__ . '/1_Menu_Game.php';
require_once __DIR__ . '/2_Game_MetaBox.php';
require_once __DIR__ . '/3_Data.php';
