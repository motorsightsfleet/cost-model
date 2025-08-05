<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\PoliceUnit;
use App\Models\CostModelMonitoring;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PoliceUnitTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_police_unit()
    {
        $policeUnit = PoliceUnit::factory()->create([
            'police_number' => 'B 1234 AB',
            'unit_name' => 'Test Unit',
            'unit_type' => 'Kendaraan',
            'description' => 'Test description',
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('police_units', [
            'police_number' => 'B 1234 AB',
            'unit_name' => 'Test Unit',
            'unit_type' => 'Kendaraan',
            'description' => 'Test description',
            'is_active' => true,
        ]);
    }

    public function test_police_number_must_be_unique()
    {
        PoliceUnit::factory()->create(['police_number' => 'B 1234 AB']);

        $this->expectException(\Illuminate\Database\QueryException::class);
        
        PoliceUnit::factory()->create(['police_number' => 'B 1234 AB']);
    }

    public function test_can_get_active_units()
    {
        PoliceUnit::factory()->active()->count(3)->create();
        PoliceUnit::factory()->inactive()->count(2)->create();

        $activeUnits = PoliceUnit::active()->get();

        $this->assertEquals(3, $activeUnits->count());
        $this->assertTrue($activeUnits->every(fn($unit) => $unit->is_active));
    }

    public function test_can_get_police_number_attribute()
    {
        $policeUnit = PoliceUnit::factory()->create([
            'police_number' => 'B 5678 CD'
        ]);

        $this->assertEquals('B 5678 CD', $policeUnit->police_number);
    }

    public function test_has_many_monitoring_records()
    {
        $policeUnit = PoliceUnit::factory()->create();
        
        $monitoring1 = CostModelMonitoring::factory()->forPoliceUnit($policeUnit->id)->create();
        $monitoring2 = CostModelMonitoring::factory()->forPoliceUnit($policeUnit->id)->create();

        $this->assertCount(2, $policeUnit->monitoringRecords);
        $this->assertTrue($policeUnit->monitoringRecords->contains($monitoring1));
        $this->assertTrue($policeUnit->monitoringRecords->contains($monitoring2));
    }

    public function test_belongs_to_police_unit()
    {
        $policeUnit = PoliceUnit::factory()->create();
        
        $monitoring = CostModelMonitoring::factory()->forPoliceUnit($policeUnit->id)->create();

        $this->assertEquals($policeUnit->id, $monitoring->policeUnit->id);
        $this->assertEquals($policeUnit->police_number, $monitoring->policeUnit->police_number);
    }

    public function test_can_scope_by_police_number()
    {
        $policeUnit = PoliceUnit::factory()->create(['police_number' => 'B 9999 ZZ']);
        
        $foundUnit = PoliceUnit::byPoliceNumber('B 9999 ZZ')->first();
        
        $this->assertEquals($policeUnit->id, $foundUnit->id);
    }

    public function test_can_join_with_monitoring_data()
    {
        $policeUnit = PoliceUnit::factory()->create();
        $monitoring = CostModelMonitoring::factory()->forPoliceUnit($policeUnit->id)->create();

        $result = PoliceUnit::withMonitoringData()->where('police_units.id', $policeUnit->id)->first();

        $this->assertNotNull($result);
        $this->assertEquals($policeUnit->police_number, $result->police_number);
    }

    public function test_can_join_monitoring_with_police_unit()
    {
        $policeUnit = PoliceUnit::factory()->create();
        $monitoring = CostModelMonitoring::factory()->forPoliceUnit($policeUnit->id)->create();

        $result = CostModelMonitoring::withPoliceUnit()->where('cost_model_monitoring.id', $monitoring->id)->first();

        $this->assertNotNull($result);
        $this->assertEquals($policeUnit->police_number, $result->police_number);
        $this->assertEquals($policeUnit->unit_name, $result->unit_name);
    }
} 