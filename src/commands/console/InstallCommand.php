<?php
/**********************************************************************/
/*This file is part of Groovel.                                       */
/*Groovel is free software: you can redistribute it and/or modify     */
/*it under the terms of the GNU General Public License as published by*/
/*the Free Software Foundation, either version 2 of the License, or   */
/*(at your option) any later version.                                 */
/*Groovel is distributed in the hope that it will be useful,          */
/*but WITHOUT ANY WARRANTY; without even the implied warranty of      */
/*MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the       */
/*GNU General Public License for more details.                        */
/*You should have received a copy of the GNU General Public License   */
/*along with Groovel.  If not, see <http://www.gnu.org/licenses/>.    */
/**********************************************************************/

namespace commands\console;

class InstallCommand extends Command {
    protected $name = 'faq:assets';

    protected $description = 'Publishes assets for Laravel FAQ.';

    public function fire()
    {
        $this->call('faq:config');
        $this->call('faq:assets');
        if ($this->confirm('Have you configured your database yet?')) {
            $this->call('faq:config');
        } else {
            $this->comment('Your database has not been migrated, run artisan faq:migrate before use');
        }
    }
}