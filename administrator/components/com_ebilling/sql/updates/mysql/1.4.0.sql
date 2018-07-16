CREATE TABLE `#__ebilling_invoice_state` (
  `id` int(11) NOT NULL,
  `invoice_id` int(10) NOT NULL,
  `state` int(2) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT '0000-00-00 00:00:00',
  `created_by` int(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
ALTER TABLE `#__ebilling_invoice_state`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `#__ebilling_invoice_state`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;