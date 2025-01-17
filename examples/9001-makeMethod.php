<?php

    use Coco\tableManager\TableRegistry;

    require 'common.php';

//    $method = TableRegistry::makeMethod($manager->getCommentmetaTable()->getFieldsSqlMap());
//    $method = TableRegistry::makeMethod($manager->getCommentsTable()->getFieldsSqlMap());
//    $method = TableRegistry::makeMethod($manager->getLinksTable()->getFieldsSqlMap());
//    $method = TableRegistry::makeMethod($manager->getOptionsTable()->getFieldsSqlMap());
//    $method = TableRegistry::makeMethod($manager->getPostmetaTable()->getFieldsSqlMap());
//    $method = TableRegistry::makeMethod($manager->getPostsTable()->getFieldsSqlMap());
//    $method = TableRegistry::makeMethod($manager->getTermmetaTable()->getFieldsSqlMap());
//    $method = TableRegistry::makeMethod($manager->getTermRelationshipsTable()->getFieldsSqlMap());
//    $method = TableRegistry::makeMethod($manager->getTermsTable()->getFieldsSqlMap());
//    $method = TableRegistry::makeMethod($manager->getTermTaxonomyTable()->getFieldsSqlMap());
//    $method = TableRegistry::makeMethod($manager->getUsermetaTable()->getFieldsSqlMap());
    $method = TableRegistry::makeMethod($manager->getUsersTable()->getFieldsSqlMap());

    print_r($method);
