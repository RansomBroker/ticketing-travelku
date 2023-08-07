<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Session;
use Validator;
use App\Models\User;
use App\Models\Supplier;
use App\Models\Agent;
use App\Models\Produsen;
use App\Models\Deposit;
use Hash;
use PDF;

class Web extends Controller
{
    public function login()
    {
        if(Auth::check())
        {
            if(Auth::user()->role == 'admin')
            {
                return redirect()->to('admin/user');   
            } else {
                return redirect()->to('admin/agent');   
            }
        }

        return view('login');
    }

    public function auth(Request $request)
    {
        $data = [
            'email'     => $request->input('email'),
            'password'  => $request->input('password'),
        ];

        Auth::attempt($data);

        if (Auth::check()) {
            return redirect()->to('/');
        } else {
            Auth::logout();
            Session::flash('error', 'Wrong email/password');
            return redirect()->back();
        }
    }

    public function add_agent(Request $request)
    {
        $rules = [
            'email'                 => 'required|email|unique:users,email',
            // 'password'              => 'required|min:6|confirmed',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $data = $validator->messages()->toArray();
            $output = "";

            foreach ($data as $k => $v) {
                $output = str_replace(['[', ']'], ['', ''], $v[0]);
                break;
            }

            Session::flash('error', $output);
            return redirect()->back();
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        Session::flash('success', 'Agent has been registered');
        return redirect()->back();
    }

    public function fetch_agent($id)
    {
        $data = Agent::where('id', $id)->first();
        return response()->json($data);   
    }

    public function fetch_produsen($id)
    {
        $produsen = Produsen::find($id);
        return response()->json($produsen);
    }

    public function save_produsen(Request $req)
    {
        $produsen = Produsen::find($req->id);
        $produsen->name = $req->name;
        $produsen->phone = $req->phone;
        $produsen->save();
        Session::flash('success', 'Data has been saved');
        return redirect()->back();
    }

    public function edit_agent(Request $req)
    {
        $agent = Agent::find($req->id);
        $agent->sell_to = $req->sell_to;
        $agent->sell_price = str_replace('.', '', $req->sell_price);
        $agent->save();

        Session::flash('success', 'Data has been updated');
        return redirect()->back();
    }

    public function delete_agent($id)
    {
        $user = User::find($id);
        $user->delete();
        Session::flash('success', 'Agent has been deleted');
        return redirect()->back();
    }

    public function user()
    {
        $data['user'] = User::where('role', 'agent')->get();
        return view('user', $data);
    }

    public function supplier()
    {
        $data['supplier'] = Supplier::selectRaw('*, supplier.id as supplier_id, supplier.sell_price as sells')
                            ->join('produsen', 'produsen.id', '=', 'supplier.produsen_id')
                            ->leftJoin('agent', 'supplier.id', '=', 'agent.supplier_id')
                            ->where('time_limit', '>=', date('Y-m-d H:i:s'))
                            ->get();
        $data['due'] = Supplier::selectRaw('*, supplier.sell_price as sells')
                       ->join('produsen', 'produsen.id', '=', 'supplier.produsen_id')
                       ->leftJoin('agent', 'agent.supplier_id', '=', 'supplier.id')
                       ->where('time_limit', '<=', date('Y-m-d H:i:s'))
                       ->get();
        $data['produsen'] = Produsen::all();
        return view('supplier', $data);
    }

    public function fetch_supplier($id)
    {
        $supplier = Supplier::find($id);
        return response()->json($supplier);
    }

    public function add_supplier(Request $request)
    {
        $supplier = new Supplier();
        $supplier->produsen_id = $request->produsen_id;
        $supplier->pnr = $request->pnr;
        $supplier->schedule = $request->schedule;
        $supplier->time_limit = $request->time_limit;
        $supplier->seat = $request->seat;
        $supplier->buy_price = str_replace(".","", $request->buy_price);
        $supplier->sell_price = str_replace(".","", $request->sell_price);
        $supplier->save();

        Session::flash('success', 'Data has been saved');
        return redirect()->back();
    }

    public function edit_supplier(Request $request)
    {
        $supplier = Supplier::find($request->id);
        $supplier->produsen_id = $request->produsen_id;
        $supplier->pnr = $request->pnr;
        $supplier->schedule = $request->schedule;
        $supplier->time_limit = $request->time_limit;
        $supplier->seat = $request->seat;
        $supplier->buy_price = str_replace(".","", $request->buy_price);
        $supplier->sell_price = str_replace(".","", $request->sell_price);
        $supplier->save();

        Session::flash('success', 'Data has been updated');
        return redirect()->back();
    }

    public function agent()
    {
        if(Auth::user()->role == 'agent')
        {
            $data['agent'] = User::where('role', 'agent')->where('id', Auth::user()->id)->get();
        } else {
            $data['agent'] = User::where('role', 'agent')->get();
        }


        $data['supplier_add'] = Supplier::selectRaw('*, supplier.id as supplierid, supplier.sell_price as sells')
                                      ->leftJoin('agent', 'agent.supplier_id', '=', 'supplier.id')
                                      ->where('sell_to', null)
                                      ->get();

        $data['supplier_edit'] = Supplier::selectRaw('*, supplier.id as supplierid')
                                      ->leftJoin('agent', 'agent.supplier_id', '=', 'supplier.id')
                                      ->get();

        if(Auth::user()->role == 'agent')
        {
            if(isset($_GET['month']))
            {
                $detail = explode('-', $_GET['month']);
                $month = $detail[0] < 10 ? '0'.$detail[0] : $detail[0];
                $year = $detail[1];

                $data['data'] = Agent::selectRaw('*, agent.id as agentid, produsen.name as supplier_name, agent.sell_price as sell_price, supplier.sell_price as harga_beli')
                                   ->join('supplier', 'agent.supplier_id', '=', 'supplier.id')
                                   ->where('agent_id', Auth::user()->id)
                                   ->whereMonth('agent.created_at', $month)
                                   ->whereYear('agent.created_at', $year)
                                   ->join('produsen', 'produsen.id', '=', 'supplier.produsen_id')
                                   ->join('users', 'agent.agent_id', '=', 'users.id')
                                   ->get();
            }
            else
            {
                $data['data'] = Agent::selectRaw('*, agent.id as agentid, produsen.name as supplier_name, agent.sell_price as sell_price, supplier.sell_price as harga_beli')
                                   ->join('supplier', 'agent.supplier_id', '=', 'supplier.id')
                                   ->where('agent_id', Auth::user()->id)
                                   ->join('produsen', 'produsen.id', '=', 'supplier.produsen_id')
                                   ->join('users', 'agent.agent_id', '=', 'users.id')
                                   ->get();
            }

            $sell_price  = Agent::selectRaw('supplier.sell_price')
                           ->join('supplier', 'agent.supplier_id', '=', 'supplier.id')
                           ->where('agent_id', Auth::user()->id)
                           ->sum('supplier.sell_price');
            $buy_price   = Agent::selectRaw('supplier.buy_price')
                           ->where('agent_id', Auth::user()->id)
                           ->join('supplier', 'agent.supplier_id', '=', 'supplier.id')
                           ->sum('supplier.buy_price');

            $data['margin'] = $sell_price - $buy_price;

            $final = [];

            foreach($data['data'] as $item)
            {
                $jumlah = 0;
                $depo = \DB::table('deposit')->where('agent_id', $item->agentid)->get();
                foreach($depo as $deposit)
                {
                    $jumlah += $deposit->deposit;
                }

                if($jumlah == 0)
                {
                    $item->deposit = 'Tersimpan';
                }
                else if($jumlah >= ($item->sell_price * $item->seat))
                {
                    $item->deposit = 'Lunas';
                } 
                else 
                {
                    $item->deposit = 'Pembayaran ke - ' . count($depo);
                }

                array_push($final, $item);
            }

        } else {
            if(isset($_GET['month']))
            {
                $detail = explode('-', $_GET['month']);
                $month = $detail[0] < 10 ? '0'.$detail[0] : $detail[0];
                $year = $detail[1];

                $data['data'] = Agent::selectRaw('*, agent.id as agentid, produsen.name as supplier_name, agent.sell_price as sell_price, supplier.sell_price as harga_beli')
                            ->whereMonth('agent.created_at', $month)
                            ->whereYear('agent.created_at', $year)
                            ->join('supplier', 'agent.supplier_id', '=', 'supplier.id')
                            ->join('produsen', 'produsen.id', '=', 'supplier.produsen_id')
                            ->join('users', 'agent.agent_id', '=', 'users.id')->get();
            }
            else
            {
                $data['data'] = Agent::selectRaw('*, agent.id as agentid, produsen.name as supplier_name, agent.sell_price as sell_price, supplier.sell_price as harga_beli')
                            ->join('supplier', 'agent.supplier_id', '=', 'supplier.id')
                            ->join('produsen', 'produsen.id', '=', 'supplier.produsen_id')
                            ->join('users', 'agent.agent_id', '=', 'users.id')->get();
            }
            
            $sell_price  = Agent::selectRaw('supplier.sell_price')
                            ->join('supplier', 'agent.supplier_id', '=', 'supplier.id')->sum('supplier.sell_price');
            $buy_price   = Agent::selectRaw('supplier.buy_price')
                            ->join('supplier', 'agent.supplier_id', '=', 'supplier.id')->sum('supplier.buy_price');

            $data['margin'] = $sell_price - $buy_price;

            $final = [];

            foreach($data['data'] as $item)
            {
                $jumlah = 0;
                $depo = \DB::table('deposit')->where('agent_id', $item->agentid)->get();
                foreach($depo as $deposit)
                {
                    $jumlah += $deposit->deposit;
                }

                if($jumlah == 0)
                {
                    $item->deposit = 'Tersimpan';
                }
                else if($jumlah >= ($item->sell_price * $item->seat))
                {
                    $item->deposit = 'Lunas';
                } 
                else 
                {
                    $item->deposit = 'Pembayaran ke - ' . count($depo);
                }

                array_push($final, $item);
            }
        }

        $data['data'] = $final;
        
        return view('agent', $data);
    }

    public function add_agents(Request $request)
    {
        $agent = new Agent();
        $agent->agent_id = $request->agent_id;
        $agent->supplier_id = $request->supplier_id;
        $agent->sell_price = str_replace('.', '', $request->sell_price);
        $agent->sell_to = $request->sell_to;
        $agent->acceptance = 'accept';
        $agent->save();

        Session::flash('success', 'Data has been added');
        return redirect()->back();
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->to('login');
    }

    public function produsen()
    {
        $data['user'] = Produsen::all();
        return view('produsen', $data);
    }

    public function add_produsen(Request $request)
    {
        $produsen = new Produsen();
        $produsen->name = $request->name;
        $produsen->phone = $request->phone;
        $produsen->save();

        Session::flash('success', 'Data has been saved');
        return redirect()->back();
    }

    public function delete_produsen($id)
    {
        $produsen = Produsen::find($id);
        $produsen->delete();

        Session::flash('success', 'Data has been deleted');
        return redirect()->back();
    }

    public function delete_supplier($id)
    {
        $supplier = Supplier::find($id);
        $supplier->delete();

        Session::flash('success', 'Data has been deleted');
        return redirect()->back();
    }

    public function deleting_agent($id)
    {
        $agent = Agent::find($id);
        $agent->delete();

        Session::flash('success', 'Data has been deleted');
        return redirect()->back();
    }

    public function print($id)
    {
        $data['data'] = Agent::selectRaw('*, agent.created_at as tanggal_terbit, agent.id as agentid, produsen.name as supplier_name, agent.sell_price as sell_price, users.name as agent_name')
                                   ->join('supplier', 'agent.supplier_id', '=', 'supplier.id')
                                   ->join('produsen', 'produsen.id', '=', 'supplier.produsen_id')
                                   ->join('users', 'agent.agent_id', '=', 'users.id')
                                   ->where('agent.id', $id)
                                   ->first();

        $data['data']->schedule = str_replace("\n", "<br>", $data['data']->schedule);

        $data['total_deposit'] = \DB::table('deposit')->where('agent_id', $id)->sum('deposit');
        $data['deposit'] = \DB::table('deposit')->where('agent_id', $id)->get();

        $data['logo'] = 'assets/img/logo.jpg';

        $pdf = Pdf::loadView('print', $data);
        return $pdf->download('invoice.pdf');
    }

    public function view_info($id)
    {
        $data['data'] = Agent::selectRaw('*, agent.created_at as tanggal_terbit, agent.id as agentid, produsen.name as supplier_name, agent.sell_price as sell_price, users.name as agent_name')
                                   ->join('supplier', 'agent.supplier_id', '=', 'supplier.id')
                                   ->join('produsen', 'produsen.id', '=', 'supplier.produsen_id')
                                   ->join('users', 'agent.agent_id', '=', 'users.id')
                                   ->where('agent.id', $id)
                                   ->first();

        $data['data']->schedule = str_replace("\n", "<br>", $data['data']->schedule);

        $data['total_deposit'] = \DB::table('deposit')->where('agent_id', $id)->sum('deposit');
        $data['deposit'] = \DB::table('deposit')->where('agent_id', $id)->get();
        $data['logo'] = asset('assets/img/logo.jpg');

        return view('print', $data);
    }

    public function fetch_deposit($id)
    {
        $deposit = \DB::table('deposit')->where('agent_id', $id)->get();
        return response()->json($deposit);
    }

    public function delete_deposit($id)
    {
        $deposit = \DB::table('deposit')->where('id', $id)->delete();
        Session::flash('success', 'Data has been deleted');
        return redirect()->back();
    }

    public function add_deposit(Request $req)
    {
        $agent = Agent::find($req->agent_id);
        $agent->acceptance = 'pending';
        $agent->save();

        $deposit = \DB::table('deposit')->insert([
            'agent_id' => $req->agent_id,
            'date' => $req->date,
            'deposit' => str_replace('.', '', $req->deposit),
        ]);

        Session::flash('success', 'Data has been saved');
        return redirect()->back();
    }

    public function report()
    {
        $array = [];
        $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        $data['month'] = [];

        if(isset($_GET['from']) && $_GET['to']) {
            $from = explode('-', $_GET['from']);
            $to = explode('-', $_GET['to']);

            $from[1] = str_replace('0', '', $from[1]);

            for($i = $from[1]; $i <= $to[1]; $i++) {
                $month = ($i < 10) ? '0'.$i : $i;
                if(Auth::user()->role == 'agent')
                {
                    $item = Deposit::selectRaw('*, agent.created_at as time')
                                  ->leftJoin('agent', 'deposit.agent_id', '=', 'agent.id')
                                  ->join('supplier', 'agent.supplier_id', '=', 'supplier.id')
                                  ->where('acceptance', 'accept')
                                  ->whereMonth('agent.created_at', '=', $i)
                                  ->whereYear('agent.created_at', '=', $to[0])
                                  ->where('agent.agent_id', Auth::user()->id)
                                  ->sum('deposit.deposit');
                } else {
                    $item = Deposit::selectRaw('*, agent.created_at as time')
                                  ->leftJoin('agent', 'deposit.agent_id', '=', 'agent.id')
                                  ->join('supplier', 'agent.supplier_id', '=', 'supplier.id')
                                  ->where('acceptance', 'accept')
                                  ->whereMonth('agent.created_at', '=', $i)
                                  ->whereYear('agent.created_at', '=', $to[0])
                                  ->sum('deposit.deposit');
                }
                
                $o = (object)[
                    'jumlah' => $item,
                    'bulan' => $month . '-' . $from[0]
                ];

                array_push($array, $o);
                array_push($data['month'], $months[$i - 1]);
            }

            $data['year'] = $from[0];
        } else {
            for($i = 1; $i <= 12; $i++) {
                $month = ($i < 10) ? '0'.$i : $i;
                if(Auth::user()->role == 'agent')
                {
                    $item = Deposit::selectRaw('*, agent.created_at as time')
                                      ->leftJoin('agent', 'deposit.agent_id', '=', 'agent.id')
                                      ->join('supplier', 'agent.supplier_id', '=', 'supplier.id')
                                      ->where('acceptance', 'accept')
                                      ->where('sell_to', '!=', null)
                                      ->whereMonth('agent.created_at', '=', $month)
                                      ->whereYear('agent.created_at', '=', date("Y"))
                                      ->where('agent.agent_id', Auth::user()->id)
                                      ->sum('deposit.deposit');
                                      
                } else {
                    $item = Deposit::selectRaw('*, agent.created_at as time')
                                      ->leftJoin('agent', 'deposit.agent_id', '=', 'agent.id')
                                      ->join('supplier', 'agent.supplier_id', '=', 'supplier.id')
                                      ->where('acceptance', 'accept')
                                      ->where('sell_to', '!=', null)
                                      ->whereMonth('agent.created_at', '=', $month)
                                      ->whereYear('agent.created_at', '=', date("Y"))
                                      ->sum('deposit.deposit');
                }

                $o = (object)[
                    'jumlah' => $item,
                    'bulan' => $month . '-' . date("Y")
                ];
                
                array_push($array, $o);
                array_push($data['month'], $months[$i - 1]);
            }

            $data['year'] = date("Y");
        }

        $data['report'] = $array;
        return view('report', $data);
    }

    public function manage()
    {
        $data['data'] = Agent::selectRaw('*, agent.id as agentid, produsen.name as supplier_name, agent.sell_price as sell_price')
                            ->where('acceptance', 'pending')
                            ->join('supplier', 'agent.supplier_id', '=', 'supplier.id')
                            ->join('produsen', 'produsen.id', '=', 'supplier.produsen_id')
                            ->join('users', 'agent.agent_id', '=', 'users.id')->get();
                     
        $final = [];

        foreach($data['data'] as $item)
        {
            $jumlah = 0;
            $depo = \DB::table('deposit')->where('agent_id', $item->agentid)->get();
            foreach($depo as $deposit)
            {
                $jumlah += $deposit->deposit;
            }

            if($jumlah == 0)
            {
                $item->deposit = 'Tersimpan';
            }
            else if($jumlah >= ($item->sell_price * $item->seat))
            {
                $item->deposit = 'Lunas';
            } 
            else 
            {
                $item->deposit = 'Pembayaran ke - ' . count($depo);
            }

            array_push($final, $item);
        }

        $data['data'] = $final;

        return view('acceptance', $data);
    }

    public function acc_sell($id)
    {
        $agent = Agent::find($id);
        $agent->acceptance = 'accept';
        $agent->save();

        Session::flash('success', 'Data has been accepted');
        return redirect()->back();
    }

    public function deny_sell($id)
    {
        $depo = \DB::table('deposit')->where('agent_id', $id)->orderBy('id', 'DESC')->first();
        
        if(isset($depo->id))
        {
            \DB::table('deposit')->where('id', $depo->id)->delete();
        }

        $agent = Agent::find($id);
        $agent->acceptance = 'pending';
        $agent->save();

        Session::flash('success', 'Data has been denied');
        return redirect()->back();
    }

    public function change_password(Request $request)
    {
        $user = User::find(Auth::user()->id);

        $request->validate([
            'old_password' => 'required',
            'password' => 'required|confirmed',
        ]);

        if(!Hash::check($request->old_password, Auth::user()->password)){
            Session::flash('success', 'Password lama salah');
            return redirect()->back();
        }


        $user->password = Hash::make($request->password);
        $user->save();

        Session::flash('success', 'Data has been saved');
        return redirect()->back();

    }

    public function fetch_user($id)
    {   
        $user = User::find($id);
        return response()->json($user);
    }

    public function save_agent(Request $req)
    {
        $user = User::find($req->id);
        $user->name = $req->name;
        $user->email = $req->email;
        $user->save();

        Session::flash('success', 'Data has been saved');
        return redirect()->back();
    }
}
