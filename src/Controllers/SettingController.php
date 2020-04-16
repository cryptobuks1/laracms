<?php

namespace Laracms\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Laracms\Repositories\Contracts\OptionInterface;
use Illuminate\Support\Facades\Session;

class SettingController extends Controller
{
    /**
     * @var OptionInterface
     */
    protected $optionRepository;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct(OptionInterface $optionRepository)
    {
        $this->optionRepository = $optionRepository;
    }

    /**
     * Index
     */
    public function index()
    {
        $data = $this->optionRepository->findByNames([
            'site_name',
            'site_description',
            'site_email',
            'site_phone'
        ]);
        return view('laracms::settings.index', $data);
    }

    /**
     * Update settings
     */
    public function update(Request $request)
    {
        $get = $request->setting;
        if (count((array) $get) > 0) {
            foreach ($get as $key => $value) {
                $this->optionRepository->updateOrCreate([
                    'option_name' => (string) $key,
                    'option_value' => (string) $value,
                    'autoload' => 1
                ], [
                    'option_name' => (string) $key
                ]);
            }
        }
        Session::flash('success', 'Cập nhật thành công');
        return redirect()->back();
    }
}
