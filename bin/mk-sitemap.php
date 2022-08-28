<?php
/* No fancy autoloaders so we gotta do the manual */
require_once('../lib/Family.php');

$sitemap = '../www/sitemap.xml';

$people = Family::getAllFamily();

/* Header */
$data = []; /* Explode later on */
$data[] = '<?xml version="1.0" encoding="UTF-8"?>';
$data[] = '<urlset';
$data[] = '      xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"';
$data[] = '      xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"';
$data[] = '      xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9';
$data[] = '            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';

$data[] = '  <url>';
$data[] = '    <loc>https://heick.family/index.php</loc>';
$data[] = '    <lastmod>' . date(DATE_ATOM) . '</lastmod>';
$data[] = '    <priority>1.00</priority>';
$data[] = '  </url>';

foreach ($people as $id => $person)
{
	$data[] = '  <url>';
	$data[] = '    <loc>https://heick.family/tree.php?id=' . $id . '</loc>';
	$data[] = '    <lastmod>' . date(DATE_ATOM) . '</lastmod>';
	$data[] = '    <priority>0.10</priority>';
	$data[] = '  </url>';
}
$data[] = '</urlset>';

file_put_contents($sitemap, implode("\n", $data));
