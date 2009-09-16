<?php
/*##################################################
 *                           cache_data.class.php
 *                            -------------------
 *   begin                : September 16, 2009
 *   copyright            : (C) 2009 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
 *
 *
 ###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

interface CacheData
{
	/**
	 * Reads a property's value.
	 * @param string $name Name of the property to read
	 * @return string the read value
	 * @throws PropertyNotFoundException If the property if not found
	 */
	public function get_property(string $name);
	
	/**
	 * Sets a property value. If the property exists, it overrides its value,
	 * otherwise, it creates an entry for this property.
	 * @param string $name Name of the property
	 * @param string $value Value of the property
	 */
	public function set_property(string $name, string $value);
	
	/**
	 * Restore the default values of the properties.
	 */
	public function restore_default();
}

?>