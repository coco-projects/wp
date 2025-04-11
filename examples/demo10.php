<?php

    require 'common.php';

   $res = $manager->getTermsByTaxonomy('category',['分类1']);

   print_r($res);exit;;
