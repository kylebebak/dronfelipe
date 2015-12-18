<meta written="2014-09-20" slug="events_moving_window" name="Counting events in a moving window" description="How to use a queue to count the number of events occurring in a moving time window. Discrete event simulation." />

<header>
  <h2>
  Counting Events in a Moving Time Window
  </h2>
</header>

<h3>Detecting Anomalies</h3>
<p>
  Imagine you're writing a program that reads a series of transactions in real time and raises an alert whenever more than 50 occur in an hour. Or whenever the total value of these transactions exceeds 500 dollars. Full disclosure: I did this for a big client in Mexico that wanted to detect fraudulent transactions in their stores, both for issuing real-time alerts and for reviewing historical data. I'm not sure whether stores in the US worry about this, but in any case this sort of problem gives rise to an interesting programming model called <a href="https://en.wikipedia.org/wiki/Discrete_event_simulation">discrete event simulation</a>.
</p>

<p>
  This post doesn't explain DES &mdash; there are good explanations on the web, for example <a href="http://algs4.cs.princeton.edu/61event/">this one</a>. But here's the gist of it: DES contrasts with <b>continuous simulation</b> by only examining and updating the state of the system when an event occurs. This model is perfectly suited for the counting problem, which is one of the simplest problems DES can address.
</p>

<h3>The (Not) Moving Window</h3>
<p>
  Let's say the transactions (henceforth events) of the previous month are in a relational database. As above, we want to flag every instance in which there were more than 50 events in an hour. One approach is to select the count of events and group them by hour, but this is flawed. <b>Let's say between 10 and 10:30 there are no events, and between 10:30 and 11 there are forty. Between 11 and 11:30 there are forty more, and again there are none between 11:30 and 12.</b> The query would flag neither 10-11 nor 11-12 as suspicious, because the number of events in both hours is below the threshold. But the query would fail to notice that between 10:30 and 11:30 there were eighty events, and the threshold was exceeded.
</p>

<p>
  Clearly, we can't group by hour to count events. We need more precision, so we consider the following: for each event, we take its timestamp <code>T</code> and select the count of events that fall between <code>T</code> and <code>T + 1h</code>. This will definitely work, but it requires one query per event. This is infeasible if there are lots of events.
</p>

<p>
  But this inefficient solution leads to an insight. <b>Namely, we need an hour-long <em>"window"</em> through which we can examine blocks of consecutive events.</b> But instead of moving the window forward, event by event, we can stream the events through the window. This allows to see when the threshold is exceeded, but issues <b>just one query</b> to the database. It turns out a queue is the ideal structure for this "stationary" window.
</p>

<h3>Using the Queue</h3>
<p>
  The algorithm is dead simple. We stream the events out of the database and enqueue them in chronological order. As we enqueue each event, we compare it to the event on the front of the queue (the oldest event on the queue). If the difference in their timestamps is more than <code>1h</code>, we dequeue the oldest event. We repeat this process until the oldest event on the queue is within <code>1h</code> of the newest event of the queue. <b><em>After this, we just count the events on the queue, because they all occurred within a span of one hour</b></em>. If the count exceeds 50, well, you get the idea. Also, if we want to find hour-long intervals where the count is <b>below</b> 50, we just reverse our inequality. Here's what the code looks like:
</p>

<p>
<pre style="margin: 0; line-height: 125%">q <span style="color: #333333">=</span> Queue()
flagged_events <span style="color: #333333">=</span> <span style="color: #007020">list</span>()

<span style="color: #008800; font-weight: bold">for</span> event <span style="color: #000000; font-weight: bold">in</span> events:
    q<span style="color: #333333">.</span>enqueue(event) <span style="color: #888888"># newest event is on back of queue</span>
    back <span style="color: #333333">=</span> q<span style="color: #333333">.</span>back()
    <span style="color: #008800; font-weight: bold">while</span> back<span style="color: #333333">.</span>time() <span style="color: #333333">-</span> q<span style="color: #333333">.</span>front()<span style="color: #333333">.</span>time() <span style="color: #333333">&gt;</span> interval:
        q<span style="color: #333333">.</span>dequeue()
    <span style="color: #008800; font-weight: bold">if</span> q<span style="color: #333333">.</span>length() <span style="color: #333333">&gt;</span> threshold:
        flagged_events<span style="color: #333333">.</span>append(event)
</pre>
</p>

<h3>Performance</h3>
<p>
  Any approach to solving this problem will need to look at all the events. To undestand the performance of our approach, we consider that the queue is empty at the beginning, and is nearly empty (just one event remains) at the end.
</p>

<p>
  On average then, for every event that goes on the queue, one comes off. So, for an <b>average iteration</b> of the algorithm, we look at <b>three events</b>: the new one that goes on the back of the queue, the oldest one at the front of the queue (which usually gets dequeued), and the second oldest one (which usually doesn't get dequeued). We are reading <code>3N</code> events and making <code>2N</code> comparisons to process <code>N</code> events, which means our approach is <b>linear</b> in the number of events!
</p>

