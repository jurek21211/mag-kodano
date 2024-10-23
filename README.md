# mag-kodano
Module for interview task. Contains REST API definition, Required classes, Database definitions.\

## Instalation

To install this module in your Magento 2 environment follow these steps:
1. Paste Kodano folder into app/code/
2. Run setup:upgrade and setup:di:compile
3. Check if app/etc/config.php contains this entry. 'Kodano_Interview' => 1, if so, your module is ready to go.

 ## DB
Tables: 
kd_promotions -> table containing prromotions
kd_promotion_group -> table containing promotion groups
kd_promotions_promotion_group -> table containg relation between promotion and promotion_group (MANY TO MANY)

## API
In order to execute API calls, you must first generate authorization token for Magento user.

1. [base_url]/rest/V1/integration/admin/token -> POST -> params: JSON with {username: your_username, password: your_password}

### Created API

KD_PROMOTION_GROUPS\
 1. [base_url]/rest/V1/kodano/getPromotionGroups -> GET -> no params -> list all promotion groups
 2. [base_url]/rest/V1/kodano/addPromotionGroup -> POST -> params -> JSON {groupName: your_group_name}
 3. [base_url]/rest/V1/kodano/removePromotionGroup -> DELETE -> params -> URL PARAM ?groupId=2 

KD_PROMOTIONS\
 1. [base_url]/rest/V1/kodano/getPromotions -> GET -> no params -> list all promotions with assosciated groups
 2. [base_url]/rest/V1/kodano/addPromotion -> POST -> params -> JSON {promotionName: your_promotion_name}
 3. [base_url]/rest/V1/kodano/removePromotion -> DELETE -> params -> URL PARAM ?promotionId=2 

KD_PROMOTIONS_PROMOTION_GROUP\
 1. [base_url]/rest/V1/kodano/getPromotions -> POST -> params -> JSON { "groupId": 5, "promotionId":5 }
