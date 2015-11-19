// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/*
 * @package    mod_qa
 * @copyright  2015 Skylar Kelty <S.Kelty@kent.ac.uk>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

 /**
  * @module mod_qa/view
  */
define(['jquery'], function($) {
    function init_votes() {
        $(".vote").on("click", function() {
            var button = $(this);

            require(['core/ajax', 'core/notification'], function(ajax, notification) {
                var promises = ajax.call([{
                    methodname: 'mod_qa_question_vote',
                    args: {
                        qaqid: button.attr('data-id')
                    }
                }]);

                promises[0].done(function(response) {
                    button.removeClass('voted fa-heart fa-heart-o');

                    if (response) {
                        button.addClass('voted fa-heart');
                    } else {
                        button.addClass('fa-heart-o');
                    }
                });

                promises[0].fail(notification.exception);
            });
        });
    }

    return {
        init: function() {
            init_votes();
        }
    };
});