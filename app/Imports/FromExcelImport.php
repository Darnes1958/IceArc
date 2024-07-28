<?php

namespace App\Imports;

use App\Models\FromExcel;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class FromExcelImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {

      if (!isset($row['ksm'])  || !isset($row['acc'])
        || !isset($row['ksm_date'])) {
        return null;
      }
       if (Auth::user()->IsAdmin==50) {
         $name = 'غير محدد';
         if (strlen($row['acc'])<12) $acc='00'.$row['acc']; else $acc='0'.$row['acc'];
       }
       else {
         $name=$row['name'];
         $acc=$row['acc'];
       }


      $rec= FromExcel::on(auth()->user()->company)->create(
        [
          'name' => $name,
          'acc' => $acc,
          'ksm' => $row['ksm'],
          'ksm_date' => Date::excelToDateTimeObject($row['ksm_date']),
          'bank' => 0,
          'hafitha_tajmeehy' => Auth::user()->IsAdmin,
          'h_no' => 1,

        ]
      );

      return  $rec;
    }//
    public function headingRow(): int
        {
          return Auth::user()->empno;

        }

}
