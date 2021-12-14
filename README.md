
# 代辦清單
- [ ] DEMO不能設定外觀選單
- [ ] FB轉換API
- [ ] 串接電子報
- [ ] 串接歐付寶
- [ ] 串接藍星
- [ ] 簡易模式重購
- [ ] 串接簡訊API
- [ ] 點數系統
- [ ] 串接虛擬幣付款
- [X] getButton功能
- [ ] 直銷
- [ ] 未來實作一個前端的  AJAX通知系統
- [ ] 訂單查詢聊天，狀態藍
- [ ] 整合MIN MAX


# 目標 - 自動化營運

1. 每日統計平台業績，EMAIL+LINE通知
2. 每月統計報表，EMAIL+LINE通知
3. 計算好每月要被抽多少錢
4. EMAIL跟LINE附上繳款連結
5. 時間內沒繳款  自動發催繳信跟警告
6. 期限到沒繳款，自動斷網站
7. 第二期限到，自動刪除網站


# 教學

## 1.常數

常數               | 型態  | 預設  | 說明
-------------------|:-----:|:-----:|------------------------
COMMENTS_ENABLE    | bool  | false | 是否開啟留言功能
FLUSH_METABOX      | bool  | false | 是否刷新所有使用者的Metabox設定
ONESHOP            | bool  | false | 是否為啟用一頁電商功能
EXTENSIONS_ENABLE  | bool  | false | 是否開啟擴充模組功能
FA_ENABLE          | bool  | true  | 是否載入FontAwesome資源
FLIPSTER_ENABLE    | bool  | false | 是否載入Flipster Slide資源
SLICK_ENABLE       | bool  | false | 是否載入SLICK Slide資源
TAG_ENABLE         | bool  | false | 是否啟用TAG
ROW_ACTION_ENABLE  | bool  | false | 是否啟用




## 不要更新的PLUGIN

1. users-customers-import-export-for-wp-woocommerce
因為他的多語系沒有指定text domain，所以更新可能被覆蓋掉
2. 綠界物流 - 統一超商前面要加上7-11

# Change Log

時間            | 版本號 | 說明
----------------|:-----:|------------------------
2021-09-17      | 1.1.0 | 新增getButton、oneShop等功能
