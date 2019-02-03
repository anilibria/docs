-- Simple filter

local function isempty(s)
  return s == nil or s == ''
end

local function contains(table, val)
   for i=1,#table do
      if table[i] == val then 
         return true
      end
   end
   return false
end

function split(s, delimiter)
    result = {};
    for match in (s..delimiter):gmatch("(.-)"..delimiter) do
        table.insert(result, tonumber(match));
    end
    return result;
end

function httpFloodStat(key, limit)
  xEnd = 60
  xTime = os.time()
  xStat = ngx.shared.stats:get(key)
  if isempty(xStat) then
    xCount = 1
    xStat = xCount .. "|" .. xTime+xEnd
  else
    xTmp = split(xStat, "|")
    if xTime > xTmp[2] then
      xTmp[1] = xTmp[1]-limit*(xTime-xTmp[2])
      xTmp[2] = xTime+xEnd
      if xTmp[1] < 0 then 
        xTmp[1] = 0 
      end
    end
    xCount = xTmp[1]+1
    xStat = xCount .. "|" .. xTmp[2]
  end
  ngx.shared.stats:set(key, xStat)
  --ngx.say(ngx.shared.stats:get(key))
  return xCount
end

function httpFilter(xStat, limit)
  if xStat == limit then
    ngx.log(ngx.ERR, "Warning " .. xData[1] .. " points " .. xStat)
  end
  if xStat > limit then
    ngx.status = 403
    xLog = "Banned " .. xData[1] .. " points " .. xStat
    ngx.say(xLog)
    if xStat > 10000 then
      ngx.log(ngx.ERR, xLog) -- for fail2ban
    end
    ngx.exit(ngx.HTTP_FORBIDDEN)
  end
end

wl = {"127.0.0.1", "37.1.217.18"}
if not contains(wl, ngx.var.remote_addr) then
  xData = {ngx.var.remote_addr, ngx.md5(ngx.var.remote_addr)}
  --ngx.say('identify: ' .. xData[1] .. ', hash: ' .. xData[2])
  xStat = httpFloodStat(xData[2], 20) -- 20 r/s per ip
  httpFilter(xStat, 1200) -- limit 1200 r/m
  if ngx.var.cookie_PHPSESSID then
    xData = {ngx.var.cookie_PHPSESSID, ngx.md5(ngx.var.cookie_PHPSESSID)}
    --ngx.say('identify: ' .. xData[1] .. ', hash: ' .. xData[2])
    xStat = httpFloodStat(xData[2], 5) -- 5 r/s per sess
    httpFilter(xStat, 300) -- limit 300 r/m
  end
end
