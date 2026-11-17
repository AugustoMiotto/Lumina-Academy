<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreOerMaterialRequest;
use App\Models\OerMaterial;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class OerMaterialController extends Controller
{
    /**
     * Consulta o repositório público de Recursos Educacionais Abertos - OER (RF09).
     * Utiliza Eager Loading (with) e paginação no servidor (15 por página).
     */
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));

        $materials = OerMaterial::query()
            ->with([
                'uploader:id,name,education_level,verified_credentials',
                'course:id,title,category,language',
            ])
            ->where('public', true)
            ->when($search !== '', function (Builder $query) use ($search): void {
                $escaped = str_replace(['%', '_'], ['\%', '\_'], $search);
                $query->where(function (Builder $sub) use ($escaped): void {
                    $sub->where('title', 'like', "%{$escaped}%")
                        ->orWhere('description', 'like', "%{$escaped}%");
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('oer.index', [
            'materials' => $materials,
            'search' => $search,
        ]);
    }

    /**
     * Exibe o formulário para publicação de recurso OER (RF08).
     */
    public function create(): View
    {
        return view('oer.create');
    }

    /**
     * Persiste um novo recurso educacional aberto (RF08).
     */
    public function store(StoreOerMaterialRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        OerMaterial::create([
            'uploader_id' => $request->user()->id,
            'course_id' => $validated['course_id'] ?? null,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'file_path_or_url' => $validated['file_path_or_url'],
            'public' => (bool) ($validated['public'] ?? true),
        ]);

        return redirect()
            ->route('oer.index')
            ->with('success', 'Recurso Educacional Aberto (OER) publicado com sucesso!');
    }
}
