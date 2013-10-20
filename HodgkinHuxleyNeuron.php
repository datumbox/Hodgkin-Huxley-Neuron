<?php
/** 
* Hodgkin-Huxley Neuron Model
* http://www.datumbox.com/ 
*  
* Copyright 2013, Vasilis Vryniotis 
* Licensed under MIT or GPLv3 
*/

/**
* This class simulates the Hodgkin-Huxley neuron model by using the Runge-Kutta numerical optimization method. 
* Moreover it assumes a resting potential of 0mV as described on the original paper "A quantitative description of membrane current and its application to conduction and excitation in nerve" by Hodgkin and Huxley: 
* http://www.sfn.org/~/media/SfN/Documents/ClassicPapers/ActionPotentials/hodgkin5.ashx
*/
class HodgkinHuxleyNeuron {
    
    const gNa = 120; //maximum conductance Na
    const ENa=115; //voltage measured in mV
    
    const gK=36; //maximum conductance gK
    const EK=-12; //voltage measured in mV
    
    const gL=0.3; //voltage-independent conductance
    const EL=10.6; //voltage measured in mV

    const C=1; //Capacity of neuron
    
    /**
    * Runs the Hodgkin Huxley Neuron Model by sending a constant current T for a time period of Tmax
    * 
    * @param float $I
    * @param float $Tmax
    */
    public function run($I = 10,$Tmax = 100) {
        $m=0;
        $n=0;
        $h=0;
        
        $dt=0.05;
    
        $v = array('0'=>-10);
        
        for($t=$dt; $t<=$Tmax; $t=$t+$dt) {
            
            /*
            //Uses Euler's method. (this method requires very small step size and thus is inefficient)
            $v_previous=end($v);
            $dm_dt=((2.5-0.1*$v_previous)/(exp(2.5-0.1*$v_previous)-1))    *(1-$m)  -   +4*exp(-$v_previous/18) *$m;
            $m=$m+$dt*$dm_dt;
            
            $dn_dt=((0.1-0.01*$v_previous)/(exp(1-0.1*$v_previous)-1))    *(1-$n)  -   0.125*exp(-$v_previous/80) *$n;
            $n=$n+$dt*$dn_dt;
            
            $dh_dt=(0.07*exp(-$v_previous/20))    *(1-$h)  -   1/(exp(3-0.1*$v_previous)+1) *$h;
            $h=$h+$dt*$dh_dt;
            
            
            $SumI=self::gNa*pow($m,3)*$h*($v_previous-self::ENa)+self::gK*pow($n,4)*($v_previous-self::EK)+self::gL*($v_previous-self::EL);
            $dv_dt=(-$SumI+ $I)/self::C;
            $v[(string)round($t,2)]=$v_previous+$dt*$dv_dt; 
            */
            
            //uses Runge-Kutta Method to calculate the parameters of the model.
            $v_previous=end($v); //previous value at v(t)

            
            $dm_dt=((2.5-0.1*$v_previous)/(exp(2.5-0.1*$v_previous)-1))    *(1-$m)  -   +4*exp(-$v_previous/18) *$m; //derivative dm/dt
            //Runge-Kutta Method to calculate the parameter
            $k1 = $dm_dt;
            $k2 = $dm_dt + 1/2*$dt*$k1;
            $k3 = $dm_dt + 1/2*$dt*$k2;
            $k4 = $dm_dt + 1/2*$dt*$k3;
            $m=$m+ 1/6*$dt*($k1+2*$k2+2*$k3+$k4); //estimating the new value of m
            
            $dn_dt=((0.1-0.01*$v_previous)/(exp(1-0.1*$v_previous)-1))    *(1-$n)  -   0.125*exp(-$v_previous/80) *$n; //derivative dn/dt
            //Runge-Kutta Method to calculate the parameter
            $k1 = $dn_dt;
            $k2 = $dn_dt + 1/2*$dt*$k1;
            $k3 = $dn_dt + 1/2*$dt*$k2;
            $k4 = $dn_dt + 1/2*$dt*$k3;
            $n=$n+ 1/6*$dt*($k1+2*$k2+2*$k3+$k4); //estimating the new value of n
            
            $dh_dt=(0.07*exp(-$v_previous/20))    *(1-$h)  -   1/(exp(3-0.1*$v_previous)+1) *$h; //derivative dh/dt
            //Runge-Kutta Method to calculate the parameter
            $k1 = $dh_dt;
            $k2 = $dh_dt + 1/2*$dt*$k1;
            $k3 = $dh_dt + 1/2*$dt*$k2;
            $k4 = $dh_dt + 1/2*$dt*$k3;
            $h=$h+ 1/6*$dt*($k1+2*$k2+2*$k3+$k4); //estimating the new value of h
            
            $SumI=self::gNa*pow($m,3)*$h*($v_previous-self::ENa)+self::gK*pow($n,4)*($v_previous-self::EK)+self::gL*($v_previous-self::EL); //sum all the currents
            
            $dv_dt=(-$SumI+ $I)/self::C; //derivative dv/dt
            //Runge-Kutta Method to calculate the parameter
            $k1 = $dv_dt;
            $k2 = $dv_dt + 1/2*$dt*$k1;
            $k3 = $dv_dt + 1/2*$dt*$k2;
            $k4 = $dv_dt + 1/2*$dt*$k3;
            $v[(string)round($t,2)]=$v_previous+1/6*$dt*($k1+2*$k2+2*$k3+$k4); //estimating the new value of v

        }
        
        return $v;
    }
}
