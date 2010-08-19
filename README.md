nginx ./configure Argument Generator
====================================

Overview
--------

This is a (not very) pretty web interface for generating a ./configure command
for nginx. nginx's ./configure lines are some of the longest I have to deal with
in regular administrative duties so I figured I'd lessen the load on myself a
bit. Other people might like it to. Then again they might not. Either way, w/e.

Usage
-----

1) Generate an options.php file from the auto/options file from a copy of nginx,
   using `process.pl`. This is done so that I don't have to update this every
   time there's a new version of nginx which is like every 2 weeks. Screw that.
   That's provided Igor Sysoev doesn't change the format of his ./configure help
   text. Knowing my luck he'll do that next release.

eg: ./process.pl < /usr/src/nginx/nginx-x.y.z/auto/options > options.php

2) Load up index.php in your web browser and you are good to go.
3) Start configurating your nginx nano desu~~

License
-------

Regular old (new) BSD license.
See `LICENSE.md` or `http://mastercj.net/software/LICENSE`.

Contact
-------

As always, contacting me through github would be the best way to reach me. I may
or may not respond to emails sent to mastercj [at] mastercj [dot] net.
