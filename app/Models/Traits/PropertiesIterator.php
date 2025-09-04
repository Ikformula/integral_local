<?php


namespace App\Models\Traits;


trait PropertiesIterator
{
    public function iterateAllProperties()
    {
        foreach ($this as $key => $value) {
            print "<tr>
                <td><strong>".$key."</strong></td>
                <td>".$value."</td>
            </tr>";
        }
    }

    public function breakArrObj($item){
        foreach ($item as $key => $value) {
            print "<tr>
                <td><strong>".(is_array($key) ? $this->breakArrObj($key) : $key)."</strong></td>
                <td>".$value."</td>
            </tr>";
        }
    }
}
