<?php

namespace Database\Seeders;

use App\Models\RequestModel;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RequestModelApplicationSeeder extends Seeder
{
    public function run(): void
    {

        $requests = RequestModel::all();

        $assistants = User::whereHas('roles', function ($query) {
            $query->where('name', 'assistant');
        })->whereHas('assistant', function ($query) {
            $query->where('status', 'active');
        })->get();


        if ($requests->isEmpty()) {
            return;
        }

        if ($assistants->isEmpty()) {
            return;
        }

        $applications = [
            [
                'message' => 'Tengo experiencia en limpieza y puedo ayudarte con las tareas semanales. Me adapto a tus horarios.',
                'status' => 'pending',
            ],
            [
                'message' => 'Soy profesor de matemáticas con 5 años de experiencia. Me especializo en enseñanza para niños.',
                'status' => 'accepted',
            ],
            [
                'message' => 'Me encantan los perros y tengo experiencia cuidando mascotas. Vivo cerca de tu zona.',
                'status' => 'completed',
            ],
            [
                'message' => 'Soy fontanero profesional y puedo arreglar el grifo inmediatamente.',
                'status' => 'pending',
            ],
            [
                'message' => 'Tengo experiencia cuidando plantas y puedo asegurarme de que todas reciban el cuidado adecuado.',
                'status' => 'accepted',
            ],
            [
                'message' => 'Puedo ofrecer un servicio de limpieza profesional con productos ecológicos.',
                'status' => 'rejected',
            ],
            [
                'message' => 'Soy estudiante de matemáticas y tengo experiencia dando clases particulares a niños.',
                'status' => 'pending',
            ],
            [
                'message' => 'Tengo un perro grande y experiencia en el cuidado de mascotas. Puedo adaptarme a tu horario.',
                'status' => 'rejected',
            ],
        ];

        $totalApplications = 0;

        foreach ($requests as $request) {
            
            $selectedApplications = collect($applications)->random(rand(1, min(3, count($applications))));
            
            foreach ($selectedApplications as $application) {
              
                    $assistant = $assistants->random();
                    
                    DB::table('request_model_application')->insert([
                        'request_model_id' => $request->id,
                        'user_id' => $assistant->id,
                        'message' => $application['message'],
                        'status' => $application['status'],
                        'created_at' => now()->subDays(rand(1, 30)),
                        'updated_at' => now()->subDays(rand(0, 29)),
                    ]);
                    $totalApplications++;
                
            }
        }

    }
} 