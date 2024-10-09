<?php

namespace App\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\ClassRoster;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Columns\ButtonGroupColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\ComponentColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;

class ClassRosterTable extends DataTableComponent
{
    protected $model = ClassRoster::class;

    public $classRosterId;

    public array $bulkActions = [
        'presentTutee' => 'Mark as present',
        'absentTutee' => 'Mark as absent',
    ];

    public function configure(): void
    {
        $this->setPrimaryKey('tutee_id');
        $this->setSearchLive();
        $this->setSearchEnabled();
        $this->setHideBulkActionsWhenEmptyEnabled();

        $this->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
            if ($column->isField('attendance') && $row->attendance === 'Present') {
                return [
                    'class' => 'text-green-600',
                ];
            } elseif ($column->isField('attendance') && $row->attendance === 'Absent') {
                return [
                    'class' => 'text-red-600',
                ];
            }

            return [];
        });
    }

    public function mount(int $cr_id)
    {
        $this->classRosterId = $cr_id;
    }

    public function builder(): Builder
    {
        return ClassRoster::query()
            ->where('class_rosters.class_id', $this->classRosterId)
            ->select('class_rosters.*');
    }

    public function presentTutee()
    {
        foreach ($this->getSelected() as $item) {
            $tutees = ClassRoster::where('tutee_id', (int) $item)->get();

            foreach ($tutees as $tutee) {
                $tutee->attendance = 'present';
                $tutee->save();
            }
        }

        $this->clearSelected();
    }

    public function absentTutee()
    {
        foreach ($this->getSelected() as $item) {
            $tutees = ClassRoster::where('tutee_id', (int) $item)->get();

            foreach ($tutees as $tutee) {
                $tutee->attendance = 'absent';
                $tutee->save();
            }
        }

        $this->clearSelected();
    }

    public function columns(): array
    {
        return [
            Column::make('Student Name', 'tutees.user.fname')
                ->searchable()
                ->sortable()
                ->format(fn($value, $row, Column $column) => $row->tutees->user->fname . ' ' . $row->tutees->user->lname),
            Column::make("Email Address", "tutees.user.email")
                ->sortable()
                ->searchable(),
            Column::make('Proof of payment', 'proof_of_payment')->sortable(),
            Column::make('Payment status', 'payment_status')->sortable(),
            Column::make('Attendance', 'attendance')->sortable(),
        ];
    }
}
