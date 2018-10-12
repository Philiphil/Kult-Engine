package main

import(
"net"
"fmt"
"strings"
"strconv"

"github.com/Cubox-/libping"
)

type Result struct{
  ip string
  name []string
  cname string
}

func main(){
  ips := GetLocalIPs()
  var a []string
  for _,ip := range ips{
   a = append(a, getNetworkIps(ip.String())...)
 }

 r := make([]Result, len(a))
 n := make([][]string, len(a))
 c  := make([]string, len(a))

 for i,e :=range a{
  bfr,r := net.LookupAddr(e)
  if r == nil{
    for _,eb := range bfr{
      if eb != ""{
       n[i] = append(n[i],eb)
     }
   }
 }else{
   n[i] = append(n[i],"")
 }
}
for _,e :=range a{
  bfr,r := net.LookupCNAME(e)
  if r==nil {
    c = append(c,bfr)
  }else{
    c = append(c,"")
  }
}
for i,_ :=range a{
  r[i] = Result{a[i], n[i], c[i]}
}
for i,_ := range r{
  fmt.Println( r[i].ip, r[i].name, r[i].cname );
}
}

func GetLocalIPs() []net.IPNet{
  var a []net.IPNet
  addrs, err := net.InterfaceAddrs()
  if err != nil {
    return a
  }
  for _, address := range addrs {
    if ipnet, ok := address.(*net.IPNet); ok && !ipnet.IP.IsLoopback() {
      if ipnet.IP.To4() != nil{
        a = append(a, net.IPNet{ ipnet.IP,ipnet.Mask });
      }
    }
  }
  return a
}


func getNetworkIps(ip string) []string{
  var a []string
  ip = ip[:strings.LastIndex(ip,".")+1]

  for i := 0; i < 256; i++ {
    if isIpPingable(ip+strconv.Itoa(i)){
      a = append(a, ip+strconv.Itoa(i))
    }
  }
  return a
}

func isIpPingable(ip string) bool{
  _,r := libping.Pingonce(ip);
  if r == nil {
    return true
  }
  return false
}