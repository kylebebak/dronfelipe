<h3>
  Stretching of visits: worst case scenario
</h3>


<p>
  Here we describe a visit that gets stretched as records are added to it. We define the visit's diameter <code>d</code> as the largest distance between any pair of records in the visit, and the visit's center <code>c</code> as the distance from the center to the first record in the visit.
</p>

<p>
  We consider a sequence starting with an arbitrarily placed record that instantiates a new visit <code>V</code>. The following records radiate out in one direction, such that as each record is added to <code>V</code>, the following record is at distance <code>R</code> from the previously calculated center of <code>V</code>. As each record is added, it stretches <code>V</code> as much as possible. The stretching slows down as <code>V</code> accumulates records and its "weight" increases. We want to know if this stretching is bounded, and if it's not, what function we can use to describe it.
</p>

<p>
  We focus on the growth of <code>c</code>, and hence <code>d</code>, as a function of <code>n</code>, the number of records that have been added to <code>V</code>. We assume <code>R=1</code> to simplify our expressions.
</p>

<ul class="formula">
  <li>
    `c_(n+1) = (n * c_n + (c_n + 1))/(n + 1)` <code>in general</code>
  </li>
  <li>
    `c_1 = 0` <code>first record in visit</code>
  </li>
  <li>
    `c_2 = (1 * c_1 + (c_1 + 1))/2 = (2 * c_1 + 1)/2 = c_1 + 1/2 = 1/2`
  </li>
  <li>
    `c_3 = (2 * c_2 + (c_2 + 1))/3 = (3 * c_2 + 1)/3 = c_2 + 1/3 = 1/2 + 1/3`
  </li>
  <li>
    `c_4 = (3 * c_3 + (c_3 + 1))/4 = (4 * c_3 + 1)/4 = c_3 + 1/4 = 1/2 + 1/3 + 1/4`
  </li>

</ul>

<p>
  It's clear that `AAn >= 2, c_n = 1/2 + 1/3 + ... + 1/n`. As for the diameter, the next record is always added at a distance <code>R=1</code> from the previously calculated center, so we have `AAn >= 2, d_n = 1 + c_(n-1) = sum_(i=1)^(n-1) 1/i`.  But this is just <a href="https://en.wikipedia.org/wiki/Harmonic_series_(mathematics)">the harmonic series</a>. The growth of this series is logarithmic. Specifically, `AAn >= 2, d_n < ln(n)`.
</p>
